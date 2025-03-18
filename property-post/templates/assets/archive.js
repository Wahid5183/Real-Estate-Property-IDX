document.addEventListener('DOMContentLoaded', function() {

    
    // Price Range Slider
    const minInput = document.getElementById('price_min');
    const maxInput = document.getElementById('price_max');
    const highlight = document.getElementById('range-highlight');
    const minValue = document.getElementById('price_min_value');
    const maxValue = document.getElementById('price_max_value');
    const maxPrice = 1000000;

    function updateValues() {
        const min = parseInt(minInput.value);
        const max = parseInt(maxInput.value);
        
        minValue.textContent = `$${min.toLocaleString()}`;
        maxValue.textContent = `$${max.toLocaleString()}`;
        
        const left = (min / maxPrice) * 100;
        const right = 100 - (max / maxPrice) * 100;
        highlight.style.left = `${left}%`;
        highlight.style.right = `${right}%`;
    }

    if(minInput && maxInput) {
        minInput.addEventListener('input', function() {
            if (parseInt(this.value) > parseInt(maxInput.value)) {
                maxInput.value = this.value;
            }
            updateValues();
        });

        maxInput.addEventListener('input', function() {
            if (parseInt(this.value) < parseInt(minInput.value)) {
                minInput.value = this.value;
            }
            updateValues();
        });
        updateValues();
    }

    // Square Footage Slider
    const sqft_minInput = document.getElementById('sqft_min');
    const sqft_maxInput = document.getElementById('sqft_max');
    const sqft_highlight = document.getElementById('range-highlight-home');
    const sqft_minValue = document.getElementById('home_min_value');
    const sqft_maxValue = document.getElementById('home_max_value');
    const sqft_maxPrice = 6000;

    function sqft_updateValues() {
        const min = parseInt(sqft_minInput.value);
        const max = parseInt(sqft_maxInput.value);
        
        sqft_minValue.textContent = `${min.toLocaleString()} sqft`;
        sqft_maxValue.textContent = `${max.toLocaleString()} sqft`;
        
        const left = (min / sqft_maxPrice) * 100; // Fixed variable name
        const right = 100 - (max / sqft_maxPrice) * 100;
        sqft_highlight.style.left = `${left}%`;
        sqft_highlight.style.right = `${right}%`;
    }

    if(sqft_minInput && sqft_maxInput) {
        sqft_minInput.addEventListener('input', function() {
            if (parseInt(this.value) > parseInt(sqft_maxInput.value)) {
                sqft_maxInput.value = this.value;
            }
            sqft_updateValues();
        });

        sqft_maxInput.addEventListener('input', function() {
            if (parseInt(this.value) < parseInt(sqft_minInput.value)) {
                sqft_minInput.value = this.value;
            }
            sqft_updateValues();
        });
        sqft_updateValues();
    }
});