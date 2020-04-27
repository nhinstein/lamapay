window.dt = require('datatables.net').default;
require('@fortawesome/fontawesome-free/js/all')
import moment from "moment-timezone";
import daterangepicker from "daterangepicker"; // don't delete daterangepicker it's used to filter report date
import Highcharts from "highcharts";
import Exporting from "highcharts/modules/exporting";

Exporting(Highcharts);


$(function () {


    $('#tax_id').on('change', function () {
        var tax_rate = $(this).find(':selected').attr('data-tax_rate')
        document.getElementById("tax_rate").value = tax_rate;
    });

    $('#opening_time').datetimepicker({
        format: "HH:mm",
    });

    $('#closing_time').datetimepicker({
        format: "HH:mm",
    });

    $('#break_time_start').datetimepicker({
        format: "HH:mm",
    });

    $('#break_time_end').datetimepicker({
        format: "HH:mm",
    });

    $('#vehicle_reg_date').datetimepicker({
        format: "YYYY-MM-DD",
    });

    if ($('#register-timezone')[0]) {
        let tz = moment.tz.guess();
        $('#register-timezone').val(tz);
    }

    let dtable, dtable_object;
    $('[data-tables]').each(function () {
        let option = $(this).data('options');
        let _option = {
            language: {
                info: "<span class='dt-style'>Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> entries</span>",
                infoEmpty: "",
                lengthMenu: "<span class='dt-style'>Show</span> _MENU_ <span class='dt-style'>entries</span>",
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
            initComplete: function () {
                $('.dataTables_wrapper select').select2({
                    minimumResultsForSearch: -1,
                });
            },
        };
        let options = Object.assign(option, _option);
        dtable = $(this).DataTable(options);
        dtable_object = $(this);
    });

    $('.dataTables_wrapper .filter-input').on('keyup', function () {
        dtable.column($(this).data('column')).search($(this).val()).draw();
    });

    $('.dataTables_wrapper .filter-label').on('click touch', function () {
        if ($('tfoot tr:first-child', dtable_object).is(":hidden"))
            $(this).removeClass('filter-label-down').addClass('filter-label-up');
        else
            $(this).removeClass('filter-label-up').addClass('filter-label-down');
        dtable_object.find('tfoot tr:first-child').toggle();
    });

    // slug helper
    $('#role_name').on('keyup', function () {
        var slug = $(this).val();
        slug = slug.toLowerCase();

        var regExp = /\s+/g;
        slug = slug.replace(regExp, '-');

        $('#role_slug').val(slug);
    });

    var demo1 = $('select[name="inspection_id[]"]').bootstrapDualListbox({
        moveOnSelect: false,
    });

    // Select2
    $('.select2-country').select2({
        placeholder: 'Choose Country',
        minimumInputLength: 1,
        ajax: {
            url: '/ajax/country',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {

                return {
                    results: $.map(data, function (name, id) {
                        return {
                            text: name,
                            id: id
                        }
                    })
                };
            },
            cache: true
        }
    });

    // show list car_make
    $('#vehicle_master_car_make_id').on('change', function () {
        var car_make_id = $(this).val();
        var car_make_name = $('#vehicle_master_car_make_id option:selected').html();

        if (car_make_id) {

            $.ajax({
                url: '/member/customer-vehicle/vehicle/model/' + car_make_id,
                type: 'GET',
                datatype: 'json',
                success: function (data) {
                    $('#vehicle_master_car_model_id').html(data)
                }
            })

        }
    });

    // edit list car_make
    $('#edit_vehicle_master_car_make_id').on('change', function () {

        var edit_make_id = $(this).val();
        var vehicle_uuid = $('#vehicle_uuid').val();

        if (edit_make_id) {
            $.ajax({
                url: '/member/customer-vehicle/vehicle/model/' + edit_make_id,
                type: 'GET',
                datatype: 'json',
                success: function (data) {
                    $('#edit_vehicle_master_car_model_id').html(data)
                }
            })
        }

    });

    $('#vehicle_master_car_model_id').on('change', function () {
        var car_make_id = $(this).val();
        var car_make_name = $('#vehicle_master_car_model_id option:selected').html();

        if (car_make_id) {

            $.ajax({
                url: '/member/customer-vehicle/vehicle/year/' + car_make_id,
                type: 'GET',
                datatype: 'json',
                success: function (data) {
                    $('#vehicle_master_car_model_year_id').html(data)
                }
            })

        }
    });

    $('#edit_vehicle_master_car_model_id').on('change', function () {
        var car_make_id = $(this).val();
        var car_make_name = $('#edit_vehicle_master_car_model_id option:selected').html();

        if (car_make_id) {

            $.ajax({
                url: '/member/customer-vehicle/vehicle/year/' + car_make_id,
                type: 'GET',
                datatype: 'json',
                success: function (data) {
                    $('#edit_verhicle_master_car_model_year_id').html(data)
                }
            })

        }
    });

    // icon image car
    function format(data) {
        var icon = $(
            '<span>' +
            '<img src="' + $(data.element).data('image') + '" width="30px" class="pr-2"> ' + data.text +
            '</span>'
        );
        return icon;
    }

    $('.select2-car_make_id').select2({
        placeholder: 'Choose car make',
        // allowClear: true,
        templateResult: format,
        templateSelection: function (data) {
            if (data.id === '') { // adjust for custom placeholder values
                return 'Choose car make';
            }

            return format(data);
        },
        processResults: function (data) {
            // Transforms the top-level key of the response object from 'items' to 'results'
            return {
                results: data.items
            };
        },
        escapeMarkup: function (m) {
            return m;
        }
    });

    $('.select2-vehicle_master_car_model_id').select2({
        placeholder: 'Choose car models',
        allowClear: true,
        escapeMarkup: function (m) {
            return m;
        }
    });

    $('.select2-vehicle_master_car_model_year_id').select2({
        placeholder: 'Choose car year',
        allowClear: true,
        processResults: function (data) {
            // Transforms the top-level key of the response object from 'items' to 'results'
            return {
                results: data.items
            };
        },
        escapeMarkup: function (m) {
            return m;
        }
    });

    // End Select 2
    $('.select2-roles').select2({
        placeholder: 'Choose Role',
        allowClear: true,
        //   minimumInputLength: 1,
    });

    // End Select 2
    $('.select2-supplier').select2({
        placeholder: 'Choose Supplier...',
        allowClear: true,
    }).on('select2:open', () => {
        $(".select2-results:not(:has(a))").append('<a href="/member/parts-controller/supplier/create" style="padding: 6px;height: 20px;display: inline-table;">Add New Supplier</a>');
    });

    $('.select2-vehicle_manufacture_year').select2({
        placeholder: 'Choose manufacture year',
        allowClear: true,
        //   minimumInputLength: 1,
    });

    $('#rdtp3').on('click', function () {
        $('#dtp3').attr("readonly", false);
    });

    $('#rdtp4').on('click', function () {
        $('#dtp4').attr("readonly", false);
    });

    $('#rdtp5').on('click', function () {
        $('#dtp5').attr("readonly", false);
    });

    $('#rdtp6').on('click', function () {
        $('#dtp6').attr("readonly", false);
    });

    // End Select 2
    $('.select2-parts').select2({
        placeholder: 'Choose Part',
        allowClear: true,
        minimumInputLength: 1,
    });

    $('.select2-employee').select2({
        placeholder: 'Choose Employee',
        minimumInputLength: 1,
        allowClear: true
    });

    $('.select2-work_order_status_type').select2({
        placeholder: 'Choose work order status type',
        allowClear: true,
        // minimumInputLength: 1,
    });

    $('.select2-work_order_status_core').select2({
        placeholder: 'Choose work order status core',
        allowClear: true,
        // minimumInputLength: 1,
    });

    // editService();
    //
    // $('#category_id').change(function(){
    //     var category = $(this).find("option:selected").text();
    //     if (category == 'Labour'){
    //         $("#div_service_estimate_hour").show();
    //         $("#service_estimate_hour").prop( "disabled", false );
    //     }else {
    //         $("#div_service_estimate_hour").hide();
    //         $("#service_estimate_hour").prop( "disabled", true );
    //     }
    // });
    //
    // function editService() {
    //     var current_category_id = $('#category_id').find('option:selected').text()
    //
    //     if (current_category_id == 'Labour'){
    //         $("#div_service_estimate_hour").show();
    //         $("#service_estimate_hour").prop( "disabled", false );
    //     }
    // }

    window.estimateHour = function (e) {

        var x = document.getElementById("service_estimate_hour").value;

        var hour = Math.floor(x);
        var minute = Math.round((x - hour) * 60);

        var rest_hour = hour > 0 ? hour + " Hours" : "";
        var rest_minute = minute > 0 ? minute + " Minutes" : "";

        var result = rest_hour + " " + rest_minute;

        document.getElementById("label_service_estimate_hour").innerHTML = result;
    }

    $('.select2-tax_id').select2({
        placeholder: 'Choose tax',
        allowClear: true,
        // minimumInputLength: 1,
    });

    function formatVehicle(data) {
        var icon = $(
            '<ul class="multiselect__content" style="display: inline-block;"> \n' +
            '                <li class="multiselect__element">\n' +
            '                    <span data-select="Press enter to select" data-selected="Selected" data-deselect="Press enter to remove" class="multiselect__option">\n' +
            '                        <strong>' + $(data.element).data('reg') + '</strong> <br> \n' +
            '                        <small>' + $(data.element).data('model') + '</small></span> \n' +
            '                    </span> \n' +
            '                </li>\n' +
            '            </u>'
        );
        return icon;
    }

    $('.select2-vehicle_id').select2({
        placeholder: 'Choose vehicles',
        // allowClear: true,
        templateResult: formatVehicle,
        templateSelection: function (data) {
            if (data.id === '') { // adjust for custom placeholder values
                return 'Choose vehicles';
            }

            return formatVehicle(data);
        },

        processResults: function (data) {
            // Transforms the top-level key of the response object from 'items' to 'results'
            return {
                results: data.items
            };
        },
        escapeMarkup: function (m) {
            return m;
        }
    });

    // formatCustomer
    function formatCustomer(data) {
        var icon = $(
            '<ul class="multiselect__content" style="display: inline-block;">\n' +
            '        <li class="multiselect__element">\n' +
            '                <span data-select="Press enter to select" data-selected="Selected" data-deselect="Press enter to remove" class="multiselect__option">\n' +
            '                        <strong style="line-height: 27px !important;">' + data.text + '</strong> <br> \n' +
            '                        <small><i class="fas fa-mobile-alt"></i> &nbsp;&nbsp;' + $(data.element).data('mobile') + '</small> <br>\n' +
            '                        <small><i class="fas fa-home"></i> ' + $(data.element).data('home') + '</small>\n' +
            '                </span>\n' +
            '        </li>\n' +
            '</ul>'
        );

        return icon;
    }

    $('.select2-customer_id').select2({
        placeholder: 'Choose customers',
        // allowClear: true,
        templateResult: formatCustomer,
        templateSelection: function (data) {
            if (data.id === '') { // adjust for custom placeholder values
                return 'Choose customers';
            }

            return formatCustomer(data);
        },
        processResults: function (data) {
            // Transforms the top-level key of the response object from 'items' to 'results'
            return {
                results: data.items
            };
        },
        escapeMarkup: function (m) {
            return m;
        }
    });

    $('.select2-category_id').select2({
        placeholder: 'Choose category',
        allowClear: true,
        // minimumInputLength: 1,
    });

    $('.select2-inspection_id').select2({
        placeholder: 'Choose inspection',
        allowClear: true,
    });

    $('.select2-master_level_id').select2({
        placeholder: 'Choose service level',
        allowClear: true,
    });

    $('[select2]').each(function () {
        let placeholder = $(this).attr('placehoder');
        let min_length = $(this).attr('min-length') || 0;
        $(this).select2({
            placeholder: placeholder,
            minimumInputLength: min_length,
        })
    })

    if ($('.member-balance')[0]) {
        $.getJSON('/member/ajax/balance', function (json) {
            $('.member-balance').html(json.balance);
        });
    }

    if ($('.accordion')[0]) {
        $('.accordion').accordion({
            heightStyle: 'content',
            collapsible: true
        });
    }


    if ($('#reportrange')[0]) {

        $(function () {
            // var start = moment().startOf('month');
            // var end = moment().endOf('month');

            function cb(start, end) {
                $('#reportrange span').html(start.format('D/M/Y') + ' - ' + end.format('D/M/Y'));
                $('input[name="start_date"]').val(start.format('Y-M-D'))
                $('input[name="end_date"]').val(end.format('Y-M-D'))
            }

            let json = $('#reportrange').data('value');
            let start = moment(json.start, 'Y-M-D', true);
            let end = moment(json.end, 'Y-M-D', true);
            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                }
            }, cb);

            cb(start, end);

        });
    }

    if ($('.repeater-default')[0]) {
        var data = [];
        var data_selected = [];
        var new_data = [];
        var count = 0;
        var temp = 0;
        var selected_val = [];
        var unit = [];
        initSelect2('.part-ajax');
        $.getJSON('/member/ajax/parts', function (json) {
            data = json.data;
            new_data = json.data;
            count = json.count;
            cek_val();

        });
        $(function () {
            $('.repeater-default').repeater({
                show: function () {
                    cek_val();
                    if (count > 0 && count - temp >= 0) {
                        $(this).slideDown(function () {
                            var selects = $('.part-ajax');
                            $.each(selects, function (i, selectElement) {
                                $(selectElement).removeClass('select2-hidden-accessible').next('.select2-container').remove();
                                $(selectElement).removeAttr('data-select2-id tabindex aria-hidden');
                                initSelect2(selectElement);
                                cek_val();
                            });
                        });
                    }
                },
                hide: function () {
                    $(this).remove();
                    cek_val();
                }
            });
        });

        function initSelect2(selectElement) {
            var unit = [];
            $(selectElement).select2({
                minimumInputLength: 0,
                placeholder: 'Search...',
                ajax: {
                    url: '/member/ajax/parts',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        cek_val();
                        return {
                            q: params.term,
                            selected_val: selected_val
                        }
                    },
                    processResults: function (data) {

                        return {
                            results: $.map(data.data, function (item) {
                                unit[item.id] = item.unit;
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    cache: true
                }
            }).on('select2:select', function (e) {
                var key = $(this).val();
                $(this).parent().parent().parent().find('.unit_kode').text(unit[key]);
            });
        }

        function cek_val() {
            selected_val = [];
            temp = 0;
            $(".part-ajax").each(function () {
                temp += 1;
                if ($(this).val()) {
                    selected_val.push(parseInt($(this).val()));
                }
            });

            if (count - temp <= 0) {
                $('.r-btn-add').addClass('display-none');
            } else {
                $('.r-btn-add').removeClass('display-none');
            }
        }
    }

    $('#order_date').datepicker();

    window.fullScreen = function (target) {
        $(target).toggleClass('fullscreen');
    }


    /**
     * main-work-order-list
     * main-part-request
     *      main-part-request-workorder
     *      main-part-request-selling
     * main-invoice
     * main-po
     */

    window.load_badges = function () {
        $.getJSON('/member/ajax/budges', function (json) {
            json.forEach((item) => {
                if (item.val) {
                    $(item.id).append(`<span class="badge badge-danger badge-custom">${ item.val }</span>`)
                }
            });
        });
    }

    if ($('.aside-body')[0]) {
        load_badges();

        // Socket listener
        let id = $('meta[name="uniq"]').attr('content');
        Echo.private(`Member.${id}.Badges`)
            .listen('.Badge.Refresh', (e) => {
                load_badges();
            });
    }


    if ($('.completly-steps')[0]) {
        $.getJSON('/member/ajax/completly_steps', function (json) {
            if (json.percent < 100) {
                var step = $('.completly-steps');
                step.removeClass('hide');
                step.find('.progress-bar-text').find('strong').text(json.text);
                step.find('a.btn-here').attr('href', json.current_link);
                step.find('.progress-bar-work').css({width: `${json.percent}%`});
            }
        });
    }

    if ($('#report-summary')[0]) {
        var chartColumn = $('#report-summary');
        Highcharts.chart('report-summary', {
            chart: {
                type: 'bar'
            },
            title: {
                text: chartColumn.data('title') || ''
            },
            subtitle: {
                text: chartColumn.data('sub-title') || ''
            },
            xAxis: {
                categories: chartColumn.data('categories') || [],
                crosshair: true,
                title: {
                    text: null
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total Comparation',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ' '
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: chartColumn.data('series') || [],
        });
    }

    if ($('#report-collection')[0]) {
        var chartColumn = $('#report-collection');
        Highcharts.chart('report-collection', {
            chart: {
                type: 'column'
            },
            title: {
                text: chartColumn.data('title') || 'Chart'
            },
            subtitle: {
                text: chartColumn.data('sub-title') || 'Sub Title'
            },
            xAxis: {
                categories: chartColumn.data('categories') || [],
                crosshair: true,
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Value'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: chartColumn.data('series') || [],
        });

        var url_ajax = '/member/ajax/payment_report';
        create_chart(url_ajax, 'Payment Method', 'pie-container')

        var url_state_inv = '/member/ajax/invoice_state_report';
        create_chart(url_state_inv, 'PAID & UNPAID', 'pie-state')
    }


    function create_chart(url, title, el) {
        var start_date = $('input[name=start_date]').val();
        var end_date = $('input[name=end_date]').val();
        var url_ajax = url + '?start_date=' + start_date + '&end_date=' + end_date;
        $.getJSON(url_ajax, function (json) {
            $(function () {
                Highcharts.chart(el, {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: title
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            size: 250,
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        name: 'Brands',
                        colorByPoint: true,
                        data: json.data
                    }]
                });
            });
        });
    }

    $('#example1').DataTable({
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        }
    });

});

// requires jquery library
jQuery(document).ready(function () {
    jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
});
