import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

/*
 * แผนที่ใช้ Leaflet + OpenStreetMap (ฟรี ไม่ต้องใช้ API key)
 * Alpine มากับ Livewire 3 อยู่แล้ว จึงลงทะเบียนคอมโพเนนต์ผ่าน alpine:init
 */

const TILE_URL = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
const TILE_ATTR = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>';
const DEFAULT_CENTER = [14.3415, 100.5650]; // เกาะเมืองอยุธยา

function pinIcon(icon, color) {
    return L.divIcon({
        className: '',
        html: `<div class="map-pin" style="--pin:${color}"><span>${icon}</span></div>`,
        iconSize: [38, 46],
        iconAnchor: [19, 44],
        popupAnchor: [0, -40],
    });
}

function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

function popupHtml(m) {
    const meta = [m.faith ? `ศาสนา${m.faith}` : m.type, m.community].filter(Boolean).map(escapeHtml).join(' · ');
    const dist = m.distance_km != null ? `<p class="map-popup-dist">ห่างจากคุณ ${Number(m.distance_km).toFixed(1)} กม.</p>` : '';
    return `
        <div class="map-popup">
            <p class="map-popup-title">${escapeHtml(m.icon)} ${escapeHtml(m.name)}</p>
            <p class="map-popup-meta">${meta}</p>
            ${dist}
            <div class="map-popup-actions">
                <a href="${m.url}" wire:navigate>รายละเอียด</a>
                ${m.directions ? `<a href="${m.directions}" target="_blank" rel="noopener">นำทาง ↗</a>` : ''}
            </div>
        </div>`;
}

document.addEventListener('alpine:init', () => {
    /**
     * แผนที่แสดงหมุดหลายจุด
     * <div x-data="placesMap({ markers: [...], fit: true })" wire:ignore>
     *   <div x-ref="map" class="h-96"></div>
     * </div>
     * อัปเดตหมุดจากฝั่ง Livewire: $this->dispatch('markers-updated', markers: [...])
     */
    window.Alpine.data('placesMap', ({ markers = [], center = null, zoom = 14, fit = true } = {}) => ({
        map: null,
        layer: null,
        userMarker: null,
        locating: false,
        locateError: '',

        init() {
            this.map = L.map(this.$refs.map, { scrollWheelZoom: false })
                .setView(center ?? DEFAULT_CENTER, zoom);
            L.tileLayer(TILE_URL, { attribution: TILE_ATTR, maxZoom: 19 }).addTo(this.map);
            this.layer = L.featureGroup().addTo(this.map);
            this.render(markers, fit);

            // เปิด scroll zoom เมื่อคลิกแผนที่ (กันเลื่อนหน้าแล้วแผนที่ซูมโดยไม่ตั้งใจ)
            this.map.on('click', () => this.map.scrollWheelZoom.enable());

            this._onUpdate = (e) => this.render(e.detail.markers ?? [], true);
            window.addEventListener('markers-updated', this._onUpdate);
            this._onFocus = (e) => this.focus(e.detail.id);
            window.addEventListener('focus-marker', this._onFocus);
        },

        destroy() {
            window.removeEventListener('markers-updated', this._onUpdate);
            window.removeEventListener('focus-marker', this._onFocus);
            this.map?.remove();
        },

        render(list, fitBounds) {
            this.layer.clearLayers();
            this.byId = {};
            list.filter((m) => m.lat && m.lng).forEach((m) => {
                const marker = L.marker([m.lat, m.lng], { icon: pinIcon(m.icon, m.color) })
                    .bindPopup(popupHtml(m))
                    .addTo(this.layer);
                this.byId[m.id] = marker;
            });
            if (fitBounds && this.layer.getLayers().length > 1) {
                const group = this.userMarker ? L.featureGroup([...this.layer.getLayers(), this.userMarker]) : this.layer;
                this.map.fitBounds(group.getBounds().pad(0.15));
            } else if (this.layer.getLayers().length === 1) {
                this.map.setView(this.layer.getLayers()[0].getLatLng(), 16);
            }
        },

        focus(id) {
            const m = this.byId?.[id];
            if (!m) return;
            this.map.setView(m.getLatLng(), 17, { animate: true });
            m.openPopup();
            this.$refs.map.scrollIntoView({ behavior: 'smooth', block: 'center' });
        },

        /** หาตำแหน่งผู้ใช้ แล้วส่งให้ Livewire คำนวณระยะทาง */
        locate() {
            if (!navigator.geolocation) {
                this.locateError = 'เบราว์เซอร์นี้ไม่รองรับการระบุตำแหน่ง';
                return;
            }
            this.locating = true;
            this.locateError = '';
            navigator.geolocation.getCurrentPosition(
                ({ coords }) => {
                    this.locating = false;
                    const latlng = [coords.latitude, coords.longitude];
                    this.userMarker?.remove();
                    this.userMarker = L.circleMarker(latlng, {
                        radius: 9, color: '#fff', weight: 3, fillColor: '#2563EB', fillOpacity: 1,
                    }).bindPopup('ตำแหน่งของคุณ').addTo(this.map);
                    this.$wire.setLocation(coords.latitude, coords.longitude);
                },
                () => {
                    this.locating = false;
                    this.locateError = 'ไม่สามารถระบุตำแหน่งได้ กรุณาอนุญาตการเข้าถึงตำแหน่ง';
                },
                { enableHighAccuracy: true, timeout: 10000 },
            );
        },
    }));

    /**
     * ตัวเลือกพิกัดสำหรับหลังบ้าน: คลิก/ลากหมุดเพื่อกำหนด lat/lng
     * <div x-data="locationPicker({ lat: @entangle('form.lat'), lng: @entangle('form.lng') })" wire:ignore>
     */
    window.Alpine.data('locationPicker', ({ lat, lng }) => ({
        lat, lng, map: null, marker: null,

        init() {
            const start = this.lat && this.lng ? [this.lat, this.lng] : DEFAULT_CENTER;
            this.map = L.map(this.$refs.map).setView(start, this.lat ? 16 : 13);
            L.tileLayer(TILE_URL, { attribution: TILE_ATTR, maxZoom: 19 }).addTo(this.map);
            if (this.lat && this.lng) this.place(start);

            this.map.on('click', (e) => this.place([e.latlng.lat, e.latlng.lng]));

            // ถ้าพิมพ์พิกัดเองในช่อง input ให้หมุดย้ายตาม
            const sync = () => {
                const la = parseFloat(this.lat), ln = parseFloat(this.lng);
                if (isNaN(la) || isNaN(ln)) return;
                const cur = this.marker?.getLatLng();
                if (cur && Math.abs(cur.lat - la) < 1e-7 && Math.abs(cur.lng - ln) < 1e-7) return;
                this.place([la, ln]);
                this.map.setView([la, ln], Math.max(this.map.getZoom(), 15));
            };
            this.$watch('lat', sync);
            this.$watch('lng', sync);
            // แผนที่ใน modal ต้องคำนวณขนาดใหม่หลังแสดงผล
            setTimeout(() => this.map.invalidateSize(), 300);
        },

        destroy() {
            this.map?.remove();
        },

        place([la, ln]) {
            this.lat = Number(la.toFixed(7));
            this.lng = Number(ln.toFixed(7));
            if (!this.marker) {
                this.marker = L.marker([la, ln], { draggable: true, icon: pinIcon('📍', '#2F9660') }).addTo(this.map);
                this.marker.on('dragend', () => this.place([this.marker.getLatLng().lat, this.marker.getLatLng().lng]));
            } else {
                this.marker.setLatLng([la, ln]);
            }
        },

        useMyLocation() {
            navigator.geolocation?.getCurrentPosition(({ coords }) => {
                this.place([coords.latitude, coords.longitude]);
                this.map.setView([coords.latitude, coords.longitude], 17);
            });
        },
    }));
});
