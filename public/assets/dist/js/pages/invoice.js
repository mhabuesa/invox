$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });
})

// Add new client
$('#clientForm').on('submit', function (e) {
    e.preventDefault();

    let form = $(this);
    let url = form.attr('action');
    let formData = form.serialize();

    // Clear previous error
    $('#client_name_error').text('');
    $('#email_error').text('');

    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        success: function (response) {
            if (response.success) {
                // Toast or alert
                showToast('Client Added', 'success');

                // Add new client to select
                $('#client').append(
                    $('<option>', {
                        value: response.client.id,
                        text: response.client.name
                    })
                );

                // Select newly added client
                $('#client').val(response.client.id);

                // Reset form
                form[0].reset();

                // Close modal
                $('#clientModal').modal('hide');
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                if (errors.client_name) {
                    $('#client_name_error').text(errors.client_name[0]);
                }
                if (errors.email) {
                    $('#email_error').text(errors.email[0]);
                }
            } else {
                alert('Something went wrong!');
            }
        }
    });
});

// Summary calculation function
$(document).ready(function () {

    // Summary calculation function
    function calculateSummary() {
        let subTotal = 0;
        let totalTax = 0;

        $('#rowContainer .card').each(function () {
            const qty = parseFloat($(this).find('.qty').val()) || 1;
            const unitPrice = parseFloat($(this).find('.unit_price').val()) || 0;
            const taxRate = parseFloat($(this).find('.tax option:selected').data('tax')) || 0;

            const amount = qty * unitPrice;
            $(this).find('.total').text(amount.toFixed(2));
            subTotal += amount;
        });

        let discount = parseFloat($('#discount').val()) || 0;
        let discountType = $('#discount_type').val();
        let discountTiming = $('#discount_timing').val();
        let discountAmount = 0;
        let taxBase = subTotal;

        // if discount before_tax
        if (discountTiming === 'before_tax') {
            if (discountType === 'percentage') {
                discountAmount = (subTotal * discount) / 100;
                $('#discount_timing').prop('disabled', false); // Disable the discount_timing field if discount type is flat
            } else {
                $('#discount_timing').prop('disabled', true); // Disable the discount_timing field if discount type is flat
                discountAmount = discount;
            }
            taxBase = subTotal - discountAmount;
        }

        // Tax calculate
        $('#rowContainer .card').each(function () {
            const qty = parseFloat($(this).find('.qty').val()) || 1;
            const unitPrice = parseFloat($(this).find('.unit_price').val()) || 0;
            const taxRate = parseFloat($(this).find('.tax option:selected').data('tax')) || 0;

            const amount = qty * unitPrice;
            const singleTaxBase = (discountTiming === 'before_tax') ?
                amount // tax will be calculated based on the original price (before discount)
                :
                amount - ((amount / subTotal) *
                    discountAmount); // tax after discount (after tax case)

            const taxAmount = singleTaxBase * taxRate / 100;
            totalTax += taxAmount;
        });

        // if discount after_tax
        if (discountTiming === 'after_tax') {
            if (discountType === 'percentage') {
                discountAmount = ((subTotal + totalTax) * discount) / 100;
                $('#discount_timing').prop('disabled', false); // Disable the discount_timing field if discount type is flat
            } else {
                $('#discount_timing').prop('disabled', true); // Disable the discount_timing field if discount type is flat
                discountAmount = discount;
            }
        }

        const grandTotal = (discountTiming === 'before_tax') ?
            (subTotal - discountAmount) + totalTax :
            (subTotal + totalTax - discountAmount);

        $('#subTotal').text(isNaN(subTotal) ? '0.00' : subTotal.toFixed(2));
        $('#totalTax').text(isNaN(totalTax) ? '0.00' : totalTax.toFixed(2));
        $('#discountTotal').text(isNaN(discountAmount) ? '0.00' : discountAmount.toFixed(2));
        $('#grandTotal').text(isNaN(grandTotal) ? '0.00' : grandTotal.toFixed(2));
    }


    // Update delete buttons show/hide
    function updateDeleteButtons() {
        const cards = $('#rowContainer .card');
        const showDelete = cards.length > 1;

        $('.delRow').each(function () {
            $(this).toggleClass('d-none', !showDelete);
        });
    }

    // unit_price set if product selected
    $(document).on('change', '.product_id', function () {
        let unitPrice = $(this).find('option:selected').data('unit_price') ?? 0 ;
        let card = $(this).closest('.card');
        card.find('.unit_price').val(unitPrice);
        calculateSummary();
    });

    // Qty/unit price/tax change
    $(document).on('input change', '.qty, .unit_price, .tax', function () {
        calculateSummary();
    });

    // Qty atlist 1 or more
    $(document).on('input', '.qty', function () {
        if ($(this).val() < 1) $(this).val(1);
        calculateSummary();
    });

    // Add row
    $('#addRow').on('click', function () {
        let originalCard = $('#rowContainer .card:first');

        // Destroy select2 before clone
        originalCard.find('.product_id').select2('destroy');

        let newCard = originalCard.clone();

        // Reset values
        newCard.find('.product_id').val('').removeAttr('data-select2-id')
            .removeClass('select2-hidden-accessible').next('.select2').remove();
        newCard.find('.qty').val(1);
        newCard.find('.unit_price').val(0);
        newCard.find('.tax').val(0);
        newCard.find('.total').text('0.00');

        // Append & reinit
        $('#rowContainer').append(newCard);
        originalCard.find('.product_id', '.tax').select2();
        newCard.find('.product_id', '.tax').select2();

        updateDeleteButtons();
        calculateSummary();
    });

    // Delete row
    $(document).on('click', '.delRow', function () {
        if ($('#rowContainer .card').length > 1) {
            $(this).closest('.card').remove();
            updateDeleteButtons();
            calculateSummary();
        }
    });

    // Discount fields change
    $(document).on('input change', '#discount, #discount_type, #discount_timing', function () {
        calculateSummary();
    });

    // Page load
    updateDeleteButtons();
    calculateSummary();
});
