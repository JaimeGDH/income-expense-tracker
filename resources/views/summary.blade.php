<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Resumen de Ingresos y Egresos por Mes y Año') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @section('content')
            <div class="container">
                <div class="accordion" id="summaryAccordion">
                    <!-- Acordeón para Ingresos -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="incomeHeading">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#incomeCollapse" aria-expanded="true" aria-controls="incomeCollapse">
                                Ingresos
                            </button>
                        </h2>
                        <div id="incomeCollapse" class="accordion-collapse collapse" aria-labelledby="incomeHeading" data-bs-parent="#summaryAccordion">
                            <div class="accordion-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Año</th>
                                            <th>Mes</th>
                                            <th>Monto Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($incomeSummary as $summary)
                                            <tr>
                                                <td>{{ $summary->year }}</td>
                                                <td>{{ strftime('%B', mktime(0, 0, 0, $summary->month, 1)) }}</td>
                                                <td>$ {{ $summary->total_amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="graphic">
                                    <canvas id="incomeChart" data-type="income"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acordeón para Egresos -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="expenseHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#expenseCollapse" aria-expanded="false" aria-controls="expenseCollapse">
                                Egresos
                            </button>
                        </h2>
                        <div id="expenseCollapse" class="accordion-collapse collapse" aria-labelledby="expenseHeading" data-bs-parent="#summaryAccordion">
                            <div class="accordion-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Año</th>
                                            <th>Mes</th>
                                            <th>Monto Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expenseSummary as $summary)
                                            <tr>
                                                <td>{{ $summary->year }}</td>
                                                <td>{{ strftime('%B', mktime(0, 0, 0, $summary->month, 1)) }}</td>
                                                <td>$ {{ $summary->total_amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="graphic">
                                    <canvas id="expenseChart" data-type="expense"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>                
                function createChart(chartId, chartData) {
                    const ctx = document.getElementById(chartId).getContext('2d');

                    const years = chartData.years;
                    const months = chartData.months;
                    const amounts = chartData.amounts;
                    
                    const chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: [{
                                label: chartData.label,
                                data: amounts,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    return chart;
                }

                

                // Datos iniciales para el gráfico de gastos (expenseSummary)
                const initialExpenseData = {
                    years: {!! json_encode($expenseSummary->pluck('year')->unique()) !!},
                    months: {!! json_encode($expenseSummary->pluck('month')->map(fn($month) => strftime('%B', mktime(0, 0, 0, $month, 1)))) !!},
                    amounts: {!! json_encode($expenseSummary->pluck('total_amount')) !!},
                    label: 'Egresos por Mes y Año'
                };

                // Datos iniciales para el gráfico de ingresos (incomeSummary)
                const initialIncomeData = {
                    years: {!! json_encode($incomeSummary->pluck('year')->unique()) !!},
                    months: {!! json_encode($incomeSummary->pluck('month')->map(fn($month) => strftime('%B', mktime(0, 0, 0, $month, 1)))) !!},
                    amounts: {!! json_encode($incomeSummary->pluck('total_amount')) !!},
                    label: 'Ingresos por Mes y Año'
                };
                
                let expenseChart;
                let incomeChart;

                // Función para cambiar los datos del gráfico cuando cambie el acordeón
                function handleAccordionChange() {
                    const expenseCollapse = document.getElementById('expenseCollapse');
                    const incomeCollapse = document.getElementById('incomeCollapse');

                    if (expenseCollapse.classList.contains('show')) {
                        // Mostrar gráfico de gastos
                        if (expenseChart instanceof Chart) {
                            expenseChart.data.labels = initialExpenseData.months;
                            expenseChart.data.datasets[0].data = initialExpenseData.amounts;
                            expenseChart.update();
                        } else {
                            expenseChart = createChart('expenseChart', initialExpenseData);
                        }
                    } else if (incomeCollapse.classList.contains('show')) {
                        // Mostrar gráfico de ingresos
                        if (incomeChart instanceof Chart) {
                            incomeChart.data.labels = initialIncomeData.months;
                            incomeChart.data.datasets[0].data = initialIncomeData.amounts;
                            incomeChart.update();
                        } else {
                            incomeChart = createChart('incomeChart', initialIncomeData);
                        }
                    }
                }

                // Crear el gráfico de gastos al inicio
                expenseChart = createChart('expenseChart', initialExpenseData);

                // Escuchar cambios en el acordeón y actualizar el gráfico en consecuencia
                const summaryAccordion = document.getElementById('summaryAccordion');
                summaryAccordion.addEventListener('shown.bs.collapse', handleAccordionChange);
            </script>




            <style>
                .graphic {
                    width: 500px;
                    margin: 0 auto;
                }
            </style>
        @endsection
    </div>
</x-app-layout>
