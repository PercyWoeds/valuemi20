
// Document ready (on load)
  function documentReady() {
    fixPngs();
    tableColors();
    textClasses();
    dropdowns();
  }


  // Add an item to a product kit
  function addItemToCredit() {
    var item = $("#ac_item").val();
    
    if(item != "") {
      var company_id = $("#invoice_company_id").val();
      var item_id = $("#ac_item_id").val();
      
      var quantity = $("#ac_item_quantity").val();
      var price = $("#ac_item_price").val();
      var discount = $("#ac_item_discount").val();    
      var items_arr = $("#items").val().split(",");

      if(quantity == "" || !isNumeric(quantity)) {
        alert("Please enter a valid quantity");
      } else if(price == "" || !isNumeric(price)) {
        alert("Please enter a valid price");
      } else if(discount == "" || !isNumeric(discount)) {
        alert("Please enter a valid discount");
      } else {
        var item_line = item_id + "|BRK|" + quantity + "|BRK|" + price + "|BRK|" + discount;
        
        $("#items").val($("#items").val() + "," + item_line);
        
        listItemsCredit();
        
        $("#ac_item_id").val("");
        $("#ac_item").val("");
        $("#ac_item_quantity").val("1");
        $("#ac_item_price").val("");
        $("#ac_item_discount").val("0");
        updateItemTotal();
      }
    } else {
      alert("Please find a product to add first.");
    }
  }

  // List items in a kit
  function listItemsCredit() {
    var items = $("#items").val();
    var company_id = $("#invoice_company_id").val();
    
    $.get('/invoices/list_items/' + company_id, {
      items: items
    },
    function(data) {
      $("#list_items").html(data);
      documentReady();
    });
  }

  // Update price total for invoice
  function updateItemTotal() {
    var quantity = $("#ac_item_quantity").val();
    var price = $("#ac_item_price").val();
    var discount = $("#ac_item_discount").val();
    
    if(isNumeric(quantity) && isNumeric(price) && isNumeric(discount)) {
      var total = quantity * price;
      total -= total * (discount / 100);

      $("#ac_item_total").html(total);
    } else {
      $("#ac_item_total").html("0.00");
    }
  }




  // On ready
  $(document).ready(function() {
    documentReady();
    
    $("#loading")
      .hide()
      .ajaxStart(function() {
        showLoading();
      })
      .ajaxStop(function() {
        hideLoading();
      })
    ;
  });




