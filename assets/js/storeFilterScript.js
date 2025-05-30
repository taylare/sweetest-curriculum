// on click event that will hide all cards that do not contain the class matching the selected filter
$("#btn-filter-products").on("click", () => {
    // create collection of product cards to easily use later
     let products = $(".card[name='product']");
    // reset any potential classes on this event function from all cards, show all again in case user selects no filter
    // and wishes to see all products again
    products.removeClass("hide");
    products.show();
    // create sentinel to test if any boxes have been checked
    let boxChecked = false;
    // create a collection of the checkbox values
    let checkboxes = $("input[type='checkbox'][name='category']");
    // check to see if at least one checkbox as been selected
    if(checkboxes.prop("checked" === true)) {   
    }
    // create empty array to store the filters in
    let currentFilters = [];
    // iterate over each chechbox, add their value to the array of filters (use for of loop not for in when iterating over query objects)
    for (let checkbox of checkboxes) {
        // check if checkbox is checked by user
        if ($(`#${checkbox.id}`).prop("checked") === true) {
            // if it is, add it's Category name to the filter list
            currentFilters.push($(`#${checkbox.id}`).val());
            // set sentinel condition to true
            boxChecked = true;
        }
    }
    // apply the filtering process if a box has been checked, otherwise just make everything visible again
    if (boxChecked === true) {
        // check each card's class list to see if they have one of the filters applied
    // loop over each product card on the web page
    for (let product of products) {
        // loop over each filter in the array of currently applied filters
        // use sentinel boolean to see if the product contains a filter
        let hasFilter = false;
        for (let filter of currentFilters) {
            // check to see if the card passes the filter test by using the .contains() method on the classList
            if (product.classList.contains(filter)) {
                // set boolean to true if the filter is in the product's class list
                hasFilter = true;
            }
        }
        if (hasFilter === false) {
            product.classList.add("hide");
        }
    }
    // hide any cards with the "hide" class
    $(".hide").hide();
    } else {
        return;
    }
}); // on click event close