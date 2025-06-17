<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Site Title -->
    <title>Invoice | {{ $setting->company_name }}</title>
    <!-- Favicon -->
    @if ($setting->favicon)
        <link rel="icon" href="{{ asset($setting->favicon) }}">
    @else
        <link rel="icon" href="{{ asset('assets/dist/img/logo/favicon.png') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/invoice/style.css') }}">
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Dev Hunter">

    <style>
        .spinner-custom {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(0, 123, 255, 0.2);
            /* Light border */
            border-top-color: #007bff;
            /* Primary color */
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            vertical-align: middle;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>




</head>

<body>
    <div class="tm_container" id="invoice_content">
        <div class="tm_invoice_wrap">
            <div class="tm_invoice tm_style1 tm_type1" id="tm_download_section">
                <div class="tm_invoice_in">
                    <div class="tm_invoice_head tm_top_head tm_mb15 tm_align_center">
                        <div class="tm_invoice_left">
                            <div class="tm_logo"><img src="{{ asset($setting->logo) }}" alt="Logo"></div>
                        </div>
                        <div class="tm_invoice_right tm_text_right tm_mobile_hide">
                            <div class="tm_f50 tm_text_uppercase tm_white_color">Invoice</div>
                        </div>
                        <div class="tm_shape_bg tm_accent_bg tm_mobile_hide"></div>
                    </div>
                    <div class="tm_invoice_info tm_mb25">
                        <div class="tm_card_note tm_mobile_hide"></div>
                        <div class="tm_invoice_info_list tm_white_color">
                            <p class="tm_invoice_number tm_m0">Invoice No: <b>#{{ $invoice->invoice_number }}</b></p>
                            <p class="tm_invoice_date tm_m0">Date: <b>{{ $invoice->invoice_date->format('d-M-Y') }}</b>
                            </p>
                        </div>
                        <div class="tm_invoice_seperator tm_accent_bg"></div>
                    </div>
                    <div class="tm_invoice_head tm_mb10">
                        <div class="tm_invoice_left">
                            <p class="tm_mb2"><b class="tm_primary_color">Invoice To:</b></p>
                            <p>
                                {{ $setting->company_name }} <br>
                                {{ $setting->address }} <br>
                                <a href="{{ url('mailto:' . $setting->email) }}" class="__cf_email__"
                                    data-cfemail="5b37342c3e37371b3c363a323775383436">{{ $setting->email }}</a> <br>
                                {{ $setting->phone }}
                            </p>
                        </div>
                        <div class="tm_invoice_right tm_text_right">
                            <p class="tm_mb2"><b class="tm_primary_color">Pay To:</b></p>
                            <p>
                                {{ $invoice->client->name }} <br>
                                @if ($invoice->client->address)
                                    {{ $invoice->client->address }} <br>
                                @endif
                                @if ($invoice->client->email)
                                    {{ $invoice->client->email }} <br>
                                @endif
                                @if ($invoice->client->phone)
                                    {{ $invoice->client->phone }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="tm_table tm_style1">
                        <div class="">
                            <div class="tm_table_responsive">
                                <table>
                                    <thead>
                                        <tr class="tm_accent_bg">
                                            <th class="tm_width_3 tm_semi_bold tm_white_color">
                                                Item</th>
                                            <th class="tm_width_4 tm_semi_bold tm_white_color">
                                                Description</th>
                                            <th class="tm_width_2 tm_semi_bold tm_white_color">
                                                Price</th>
                                            <th class="tm_width_1 tm_semi_bold tm_white_color">
                                                Qty</th>
                                            <th class="tm_width_2 tm_semi_bold tm_white_color tm_text_right">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($invoice->items)
                                            @foreach ($invoice->items as $key => $item)
                                                <tr>
                                                    <td class="tm_width_3">{{ $item->product->name }}</td>
                                                    <td class="tm_width_4">{{ $item->product->description }}</td>
                                                    <td class="tm_width_1">{{ $item->qty }}</td>
                                                    <td class="tm_width_2">{{ currency($item->unit_price) }}</td>
                                                    <td class="tm_width_2 tm_text_right">
                                                        {{ currency($item->qty * $item->unit_price) }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">No items found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div
                            class="tm_invoice_footer tm_border_top tm_mb15 tm_m0_md {{ $invoice->note ? '' : 'flex_end' }}">
                            @if ($invoice->note)
                                <div class="tm_left_footer">
                                    <p class="tm_mb2"><b class="tm_primary_color">Note:</b></p>
                                    <p class="tm_m0">{{ $invoice->note }}</p>
                                </div>
                            @endif
                            <div class="tm_right_footer">
                                <table class="tm_mb15">
                                    <tbody>
                                        <tr class="tm_gray_bg ">
                                            <td class="tm_width_3 tm_primary_color tm_bold">
                                                Subtoal</td>
                                            <td class="tm_width_3 tm_primary_color tm_bold tm_text_right">
                                                {{ currency($invoice->subtotal) }}</td>
                                        </tr>
                                        <tr class="tm_gray_bg">
                                            <td class="tm_width_3 tm_primary_color">Tax</td>
                                            <td class="tm_width_3 tm_primary_color tm_text_right">
                                                +{{ currency($invoice->tax) }}</td>
                                        </tr>
                                        <tr class="tm_gray_bg">
                                            <td class="tm_width_3 tm_primary_color">Discount</td>
                                            <td class="tm_width_3 tm_primary_color tm_text_right">
                                                -{{ currency($invoice->discount_amount) }}</td>
                                        </tr>
                                        <tr class="tm_accent_bg">
                                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_white_color">
                                                Grand Total </td>
                                            <td
                                                class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_white_color tm_text_right">
                                                {{ currency($invoice->total) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($invoice_setting->authorized_status != 0)
                            <div class="tm_invoice_footer tm_type1">
                                <div class="tm_left_footer"></div>
                                <div class="tm_right_footer">
                                    <div class="tm_sign tm_text_center">
                                        <img src="{{ asset($invoice_setting->signature) }}" alt="Sign">
                                        <p class="tm_m0 tm_ternary_color">{{ $invoice_setting->name }}</p>
                                        <p class="tm_m0 tm_f16 tm_primary_color">{{ $invoice_setting->designation }}
                                        </p>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if ($invoice_setting->terms_status != 0)
                        <div class="tm_note tm_text_center tm_font_style_normal">
                            <hr class="tm_mb15">
                            <p class="tm_mb2"><b class="tm_primary_color">Terms & Conditions:</b>
                            </p>
                            <p class="tm_m0">{{ $invoice_setting->terms }}</p>
                        </div><!-- .tm_note -->
                    @endif
                </div>
            </div>
            <div class="tm_invoice_btns tm_hide_print">
                <a href="javascript:window.print()" class="tm_invoice_btn tm_color1">
                    <span class="tm_btn_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewbox="0 0 512 512">
                            <path
                                d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"
                                fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32">
                            </path>
                            <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32"
                                fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32">
                            </rect>
                            <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none"
                                stroke="currentColor" stroke-linejoin="round" stroke-width="32"></path>
                            <circle cx="392" cy="184" r="24" fill='currentColor'>
                            </circle>
                        </svg>
                    </span>
                    <span class="tm_btn_text">Print</span>
                </a>
                <a href="#" id="tm_download_btn" class="tm_invoice_btn tm_color2">
                    <span class="tm_btn_icon" id="download_icon">
                        <!-- Original Download Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <path
                                d="M320 336h76c55 0 100-21.21 100-75.6s-53-73.47-96-75.6C391.11 99.74 329 48 256 48c-69 0-113.44 45.79-128 91.2-60 5.7-112 35.88-112 98.4S70 336 136 336h56M192 400.1l64 63.9 64-63.9M256 224v224.03"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="32"></path>
                        </svg>
                    </span>
                    <span class="tm_btn_text">Download</span>
                </a>

            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.getElementById('tm_download_btn').addEventListener('click', function(e) {
            e.preventDefault();

            const buttonIcon = this.querySelector('.tm_btn_icon');
            const originalIcon = buttonIcon.innerHTML;

            // Show loading spinner
            buttonIcon.innerHTML = `<div class="spinner-custom"></div>`;

            const element = document.getElementById('invoice_content');

            const opt = {
                filename: 'invoice_{{ $invoice->invoice_number }}.pdf',
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                // Spinner stays for 0.5 second then original icon returns
                setTimeout(() => {
                    buttonIcon.innerHTML = originalIcon;
                }, 500);
            }).catch((err) => {
                console.error('PDF Error:', err);
                buttonIcon.innerHTML = originalIcon;
            });
        });
    </script>


</body>

</html>
