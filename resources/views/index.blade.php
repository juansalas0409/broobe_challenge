@extends('layouts.app')

@section('content')
    <header class="py-3">
        <div class="container px-lg-5">
            <div class="p-3 bg-light rounded-3 text-center">
                <h1 class="display-5 fw-bold">Broobe Challenge</h1>
            </div>
        </div>
    </header>

    <section class="container px-lg-5 py-3">
        <div class="d-flex justify-content-evenly p-3 bg-light rounded-3 text-center">
            <span id="run-metric" onclick="changeMainLayout(this.id)" class="border-bottom border-dark fw-bold pe-auto"
                  style="cursor: pointer">Run Metric</span>

            <span id="metric-history" onclick="changeMainLayout(this.id)"
                  class="border-bottom border-dark-subtle pe-auto" style="cursor: pointer">Metric History</span>
        </div>
    </section>

    <section data-id="main_card" data-name="run-metric" class="container px-lg-5 py-3">
        <div class="p-3 bg-light rounded-3">
            <div class="row pb-5">
                <div class="col-12 row mb-3">
                    <div class="col-md-3 col-12 mb-1 text-start mx-2">
                        <label for="url">URL<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="url">
                    </div>

                    <div class="col-md-6 col-12 mb-1 row mx-2" id="categories">
                        <span class="col-12">Categories</span>
                        @foreach($categories as $category)
                            <div class="form-check col-xl-4 col-md-6 col-12">
                                <input class="form-check-input" type="checkbox" value="{{$category->id}}"
                                       id="{{$category->name}}">
                                <label class="form-check-label" for="{{$category->name}}">
                                    {{$category->name}}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-md-2 col-12">
                        <label for="strategy">Strategy</label>
                        <select class="form-select" id="strategy">
                            <option selected></option>
                            @foreach($strategies as $strategy)
                                <option value="{{$strategy->id}}">{{$strategy->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <button type="button" id="getMetricsButton" class="btn btn-secondary w-75" onclick="getMetrics()">
                        Get metrics
                    </button>
                </div>
            </div>
            <div class="row gx-lg-5 row" id="cards" style="display: none;"></div>
            <div class="row" id="save-metrics-form" style="display: none;">
                <form action="{{route('metricHistoryRun.store')}}" method="POST" id="run-history-forms"
                      class="col-12 d-flex justify-content-center">
                    <button type="submit" class="btn btn-secondary w-75">
                        Save metrics
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section data-id="main_card" data-name="metric-history" class="container px-lg-5 py-3" style="display: none">
        <div class="p-3 bg-light rounded-3">
            <table id="myTable" class="display">
                <thead>
                <th>URL</th>
                <th>ACCESSIBILITY</th>
                <th>PWA</th>
                <th>SEO</th>
                <th>PERFORMANCE</th>
                <th>BEST PRACTICES</th>
                <th>STRATEGY</th>
                <th>DATETIME</th>
                </thead>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let errors = ("{{json_encode($errors)}}").replaceAll('&quot;', '"');
            if (errors.length > 2) {
                let error_object = JSON.parse(errors);

                Swal.fire({
                    title: 'Ocurrió un error!',
                    html: error_object.message,
                    icon: 'error',
                    confirmButtonColor: '#5C636A'
                });

                let data = error_object.data;
                let data_keys = Object.keys(data);
                let data_values = Object.values(data);

                for (let i = 0; i < data_keys.length; i++) {
                    let id;
                    if (data_keys[i].includes('_metric')) {
                        id = _.toUpper(_.snakeCase(data_keys[i].replace('_metric', '')));
                        $('#' + id).prop('checked', true);
                    } else if (data_keys[i].includes('_id')) {
                        id = (data_keys[i]).replace('_id', '');
                        $('#' + id).val(data_values[i])
                    } else {
                        id = data_keys[i];
                        $('#' + id).val(data_values[i])
                    }
                }

                makeCards(JSON.parse(sessionStorage.getItem('categories')));
            }

            $('#myTable').DataTable({
                ajax: {
                    url: '{{route('metricHistoryRun.index')}}',
                    body: {
                        _token: '{{csrf_token()}}'
                    },
                },
                columns: [
                    {data: 'url'},
                    {data: 'accessibility_metric'},
                    {data: 'pwa_metric'},
                    {data: 'seo_metric'},
                    {data: 'performance_metric'},
                    {data: 'best_practices_metric'},
                    {data: 'strategy.name'},
                    {data: 'created_at'},
                ],
                processing: true
            });
        })

        function changeMainLayout(id) {
            let element = $(`#${id}`);
            element.removeClass('border-dark-subtle');
            element.addClass('border-dark');
            element.addClass('fw-bold');

            let siblings_nodes = element.siblings()
            for (let sibling_node of siblings_nodes) {
                let sibling = $(`#${sibling_node.id}`);
                sibling.addClass('border-dark-subtle');
                sibling.removeClass('border-dark');
                sibling.removeClass('fw-bold');
            }

            let panel_to_show = $(`section[data-name=${id}]`);
            let siblings = panel_to_show.siblings('[data-id="main_card"]')
            for (let sibling of siblings) {
                $(sibling).hide();
            }
            panel_to_show.show();
        }

        function makeCards(categories) {
            sessionStorage.setItem('categories', JSON.stringify(categories));

            let properties_quantity = Object.keys(categories).length;

            let col_width = Math.floor(12 / Math.max(properties_quantity, 1)) //Le colocaco el Math.max por si acaso es 0

            col_width = Math.min(col_width, 6);
            col_width = Math.max(col_width, 4);

            let cards_parent = $("#cards");
            let run_history_forms = $("#run-history-forms");

            run_history_forms.find('input').remove();

            run_history_forms.append('<input type="hidden" name="url" value="' + $("#url").val() + '"/>')
            run_history_forms.append('<input type="hidden" name="strategy_id" value="' + $("#strategy").val() + '"/>')
            run_history_forms.append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')

            cards_parent.empty()
            for (let category of Object.values(categories)) {
                cards_parent.append(
                    '<div class="col-' + col_width + ' mb-5">' +
                    '<div class="card border">' +
                    '<div class="card-body text-center p-1">' +
                    '<p class="lead">' + category.title + '</p>' +
                    '<p class="fs-4 fw-bold">' + category.score + '</p>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                let field_name = _.snakeCase(category.id);
                run_history_forms.append('<input type="hidden" name="' + field_name + '_metric" value="' + category.score + '"/>')
            }

            cards_parent.show();
            $("#save-metrics-form").show();
        }

        function getMetrics() {
            let url = $('#url').val()
            let checkboxes = $("#categories").find("input:checked");
            let checkboxes_values = [];
            for (let checkbox of checkboxes) {
                checkboxes_values.push(checkbox.value);
            }
            let strategy = $('#strategy').val()

            if (!url) {
                Swal.fire({
                    title: 'Error!',
                    text: 'El campo URL es requerido',
                    icon: 'warning',
                    confirmButtonColor: '#5C636A'
                })
                return;
            }

            $.ajax({
                beforeSend: function () {
                    blockPage()
                },
                url: "{{route('pageSpeedMetric.getMetrics')}}",
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                },
                data: {
                    _token: "{{csrf_token()}}",
                    url: url,
                    categories_ids: checkboxes_values,
                    strategy_id: strategy
                },
                method: 'POST',
                error: function (res) {
                    unblockPage()

                    let message;
                    if (res.status == 422) {
                        let errors = JSON.parse(res.responseJSON.message);
                        let errors_fields = Object.keys(errors)
                        let errors_messages = Object.values(errors);

                        message = '<ul>'
                        for (let i = 0; i < errors_messages.length; i++) {
                            message += `<li>${errors_fields[i]}: ${errors_messages[i][0]}</li>`
                        }
                        message += '</ul>'
                    } else {
                        message = '<p>Intente de nuevo. Si el error persiste, comunicarse con soporte.</p>'
                    }

                    Swal.fire({
                        title: 'Ocurrió un error!',
                        html: message,
                        icon: 'error',
                        confirmButtonColor: '#5C636A'
                    })
                },
                success: function (data) {
                    unblockPage()
                    Swal.fire({
                        title: '',
                        icon: 'success',
                        timer: 2000,
                        confirmButtonColor: '#5C636A',
                        showConfirmButton: false
                    })
                    makeCards(data.lighthouseResult.categories)
                }
            })
        }
    </script>
@endpush
