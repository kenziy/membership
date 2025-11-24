<div 
    x-data="addressForm({
        regionCode: '{{ $regionCode ?? '' }}',
        provinceCode: '{{ $provinceCode ?? '' }}',
        cityCode: '{{ $cityCode ?? '' }}',
        barangayCode: '{{ $barangayCode ?? '' }}',
        streetValue: '{{ $streetValue ?? '' }}'
    })"

    x-init="initForm()"
>

    <h2 class="text-xl font-bold mb-4">Address</h2>

    <!-- REGION -->
    <div class="space-y-1">
        <label class="font-semibold">Region</label>
        <input 
            type="text" 
            class="w-full border p-2 rounded"
            placeholder="Type region..."
            x-model="search.region"
            x-on:input="filterRegions()"
        >
        
        <!-- Dropdown -->
        <div 
            x-show="filtered.region.length > 0" 
            class="border bg-white rounded shadow mt-1 max-h-40 overflow-y-auto"
        >
            <template x-for="item in filtered.region">
                <div 
                    class="p-2 hover:bg-blue-100 cursor-pointer"
                    @click="selectRegion(item)"
                    x-text="item.region_name"
                ></div>
            </template>
        </div>
    </div>

    <!-- PROVINCE -->
    <div class="space-y-1 mt-4">
        <label class="font-semibold">Province</label>
        <input 
            type="text" 
            class="w-full border p-2 rounded"
            placeholder="Type province..."
            x-model="search.province"
            x-on:input="filterProvinces()"
            :disabled="!selected.region"
        >

        <div 
            x-show="filtered.province.length > 0" 
            class="border bg-white rounded shadow mt-1 max-h-40 overflow-y-auto"
        >
            <template x-for="item in filtered.province">
                <div 
                    class="p-2 hover:bg-blue-100 cursor-pointer"
                    @click="selectProvince(item)"
                    x-text="item.province_name"
                ></div>
            </template>
        </div>
    </div>

    <!-- CITY -->
    <div class="space-y-1 mt-4">
        <label class="font-semibold">City / Municipality</label>
        <input 
            type="text" 
            class="w-full border p-2 rounded"
            placeholder="Type city..."
            x-model="search.city"
            x-on:input="filterCities()"
            :disabled="!selected.province"
        >

        <div 
            x-show="filtered.city.length > 0" 
            class="border bg-white rounded shadow mt-1 max-h-40 overflow-y-auto"
        >
            <template x-for="item in filtered.city">
                <div 
                    class="p-2 hover:bg-blue-100 cursor-pointer"
                    @click="selectCity(item)"
                    x-text="item.city_name"
                ></div>
            </template>
        </div>
    </div>

    <!-- BARANGAY -->
    <div class="space-y-1 mt-4">
        <label class="font-semibold">Barangay</label>
        <input 
            type="text" 
            class="w-full border p-2 rounded"
            placeholder="Type barangay..."
            x-model="search.barangay"
            x-on:input="filterBarangays()"
            :disabled="!selected.city"
        >

        <div 
            x-show="filtered.barangay.length > 0" 
            class="border bg-white rounded shadow mt-1 max-h-40 overflow-y-auto"
        >
            <template x-for="item in filtered.barangay">
                <div 
                    class="p-2 hover:bg-blue-100 cursor-pointer"
                    @click="selectBarangay(item)"
                    x-text="item.brgy_name"
                ></div>
            </template>
        </div>
    </div>

    <!-- STREET -->
    <div class="space-y-1 mt-4">
        <label class="font-semibold">Street</label>
        <input
            name="location_barangay_street"
            type="text" 
            class="w-full border p-2 rounded"
            placeholder="Street / House No..."
            x-model="street"
            :disabled="!selected.barangay"
        >
    </div>

    <!-- HIDDEN FIELDS -->
    <input type="hidden" name="location_region_code" x-model="selected.region?.region_code">
    <input type="hidden" name="location_province_code" x-model="selected.province?.province_code">
    <input type="hidden" name="location_city_code" x-model="selected.city?.city_code">
    <input type="hidden" name="location_barangay_code" x-model="selected.barangay?.brgy_code">

</div>


<script>
function addressForm(props) {
    return {
        // External values passed from Laravel
        props: props,

        region: [],
        province: [],
        city: [],
        barangay: [],

        filtered: {
            region: [],
            province: [],
            city: [],
            barangay: []
        },

        search: {
            region: "",
            province: "",
            city: "",
            barangay: ""
        },

        selected: {
            region: null,
            province: null,
            city: null,
            barangay: null,
        },

        street: "",

        async initForm() {
            await this.loadAllData();
            await this.loadExisting();
        },

        async loadAllData() {
            this.region = await (await fetch("{{ url('/location/region.json') }}")).json();
            this.province = await (await fetch("{{ url('/location/province.json') }}")).json();
            this.city = await (await fetch("{{ url('/location/city.json') }}")).json();
            this.barangay = await (await fetch("{{ url('/location/barangay.json') }}")).json();
        },

        async loadExisting() {

            // REGION
            if (this.props.regionCode) {
                this.selected.region = this.region.find(r => r.region_code === this.props.regionCode);
                this.search.region = this.selected.region?.region_name ?? "";
            }

            // PROVINCE
            if (this.props.provinceCode) {
                this.selected.province = this.province.find(p => p.province_code === this.props.provinceCode);
                this.search.province = this.selected.province?.province_name ?? "";
            }

            // CITY
            if (this.props.cityCode) {
                this.selected.city = this.city.find(c => c.city_code === this.props.cityCode);
                this.search.city = this.selected.city?.city_name ?? "";
            }

            // BARANGAY
            if (this.props.barangayCode) {
                this.selected.barangay = this.barangay.find(b => b.brgy_code === this.props.barangayCode);
                this.search.barangay = this.selected.barangay?.brgy_name ?? "";
            }

            // STREET
            this.street = this.props.streetValue ?? "";
        },

        /* FILTERS */
        filterRegions() {
            this.filtered.region = this.region.filter(r =>
                r.region_name.toLowerCase().includes(this.search.region.toLowerCase())
            );
        },

        filterProvinces() {
            if (!this.selected.region) return;

            this.filtered.province = this.province
                .filter(p => p.region_code === this.selected.region.region_code)
                .filter(p => p.province_name.toLowerCase().includes(this.search.province.toLowerCase()));
        },

        filterCities() {
            if (!this.selected.province) return;

            this.filtered.city = this.city
                .filter(c => c.province_code === this.selected.province.province_code)
                .filter(c => c.city_name.toLowerCase().includes(this.search.city.toLowerCase()));
        },

        filterBarangays() {
            if (!this.selected.city) return;

            this.filtered.barangay = this.barangay
                .filter(b => b.city_code === this.selected.city.city_code)
                .filter(b => b.brgy_name.toLowerCase().includes(this.search.barangay.toLowerCase()));
        },

        /* SELECT HANDLERS */
        selectRegion(item) {
            this.selected.region = item;
            this.search.region = item.region_name;
            this.filtered.region = [];

            this.selected.province = null;
            this.selected.city = null;
            this.selected.barangay = null;
            this.street = "";
        },

        selectProvince(item) {
            this.selected.province = item;
            this.search.province = item.province_name;
            this.filtered.province = [];

            this.selected.city = null;
            this.selected.barangay = null;
            this.street = "";
        },

        selectCity(item) {
            this.selected.city = item;
            this.search.city = item.city_name;
            this.filtered.city = [];

            this.selected.barangay = null;
            this.street = "";
        },

        selectBarangay(item) {
            this.selected.barangay = item;
            this.search.barangay = item.brgy_name;
            this.filtered.barangay = [];
        }
    }
}
</script>
