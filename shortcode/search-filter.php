<?php
function property_search_shortcode() {
    ob_start();
    ?>
    <div class="content-main-inner">
    <ul class="property-tabs">
        <li data-status="Rented/Leased" class="active">Rent</li>
        <li data-status="Sold">Sale</li>
    </ul>

    <form action="<?php echo site_url('/property/'); ?>" method="GET" class="property-search-form row row-20 justify-content-center" id="property-search-form">
        <!-- Type Select -->
        <div class="col-xs-12 col-md-3">
        <label for="property_substatus">Types</label>

                                    <select name="property_substatus" class="form-select border-0 py-1 px-1">
                                        <option value="">All Types</option>
                                        <option value="Single Family Residence" >Single Family Residence</option>
                                        <option value="Commercial Sale" >Commercial Sale</option>
                                        <option value="Rental,Multi Family" >Rental,Multi Family</option>
                                        <option value="Multi Family" >Multi Family</option>
                                    </select>
        </div>
        <!-- Keyword Input -->
        <div class="col-xs-12 col-md-3">
            <label for="property_keyword">Keywords</label>
            <input type="text" name="property_keyword" placeholder="Enter Keyword" class="property_keyword form-select border-0 py-1 px-2" 
                value="<?php echo isset($_GET['property_keyword']) ? esc_attr($_GET['property_keyword']) : ''; ?>"
                style="background: none;">
        </div>

        <!-- Location Input -->
        <div class="col-xs-12 col-md-3">
            <label for="property_location">Location</label>
            <input type="text" name="property_location" placeholder="Enter Location" class="property_location form-select border-0 py-1 px-2" 
                value="<?php echo isset($_GET['property_location']) ? esc_attr($_GET['property_location']) : ''; ?>"
                style="background: none;">
        </div>

        <input type="hidden" name="property_status" id="property_status" value="Rented/Leased">

        <!-- Search Button -->
        <button class="col-xs-12 col-md-2" type="submit">
            <i class="fa-solid fa-magnifying-glass"></i> Search
        </button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".property-tabs li");
    const propertyStatusInput = document.getElementById("property_status");

    tabs.forEach(tab => {
        tab.addEventListener("click", function () {
            tabs.forEach(t => t.classList.remove("active"));
            this.classList.add("active");
            propertyStatusInput.value = this.getAttribute("data-status");
        });
    });

    document.getElementById('property-search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const params = new URLSearchParams();
        const keyword = this.elements['property_keyword'].value.trim();
        const location = this.elements['property_location'].value.trim();
        const status = this.elements['property_status'].value.trim();
        const type = this.elements['property_substatus'].value.trim();

        if (keyword) params.set('property_keyword', keyword);
        if (location) params.set('property_location', location);
        if (status) params.set('property_status', status);
        if (type) params.set('property_substatus', type);

        window.location.href = this.action + (params.toString() ? `?${params}` : '');
    });
});
</script>

<style>
.content-main-inner {
    padding: 20px;
    background-color: #fff;
    border-radius: 3px;
    max-width: 1140px;
}

/* Tabs Styling */
.property-tabs {
    display: flex;
    gap: 20px;
    list-style: none;
    padding: 0;
    margin-bottom: 20px;
    justify-content:center
}

.property-tabs li {
    cursor: pointer;
    padding: 10px 20px;
    font-weight: bold;
    transition: 0.3s;
    font-size:16px;
}

.property-tabs li.active {
    border-bottom : 4px solid ;
}

/* Search Form Styling */
.property-search-form {
    display: flex;
    gap: 20px;
    margin: auto;
    padding: 20px;
}

.property-search-form label {
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    color: #0D263B;
    line-height: 1;
}

.property-search-form input {
    flex: 1;
    padding: 5px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.property-search-form button {
    padding: 5px 15px;
    cursor: pointer;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
}

.property-search-form button:hover {
    background-color: #0056b3;
}
</style>

    <?php
    return ob_get_clean();
}
add_shortcode('property_search', 'property_search_shortcode');