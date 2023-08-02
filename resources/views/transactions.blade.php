<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ingreso') }}
        </h2>
        
    </x-slot>

    <div class="py-12">        
        @section('content')
            <div class="container">
                <div class="row">
                    <div class="col-10">
                        <h2>Listado de {{ $transaction_type_id == 1 ? 'Ingresos' : 'Egresos' }}</h2>
                    </div>
                    <div class="col-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                        Nuevo {{ $transaction_type_id == 1 ? 'Ingreso' : 'Egreso' }}
                    </button>
                </div>
            </div>
                <div class="table-container">
                    <table class="table table-striped" id="transactions-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se insertará el contenido con JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal para registrar transacción -->
            <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTransactionModalLabel">Registrar {{ $transaction_type_id == 1 ? 'Ingreso' : 'Egreso' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Aquí va el formulario para ingresar un ingreso -->
                            <form id="addTransactionForm">
                                <!-- Campos del formulario, por ejemplo: -->
                                <div class="mb-3">
                                    <label for="date" class="form-label">Fecha</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Monto</label>
                                    <input type="number" class="form-control" id="amount" name="amount" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <input type="text" class="form-control" id="description" name="description" required>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Categoría</label>
                                    <select class="form-select" id="category" name="category_id" required>
                                        <!-- Opciones de categorías que se cargarán dinámicamente desde la API -->
                                    </select>
                                </div>
                                <!-- Agregar otros campos según tus necesidades -->
                                <input type="hidden" name="transaction_type_id" value="{{ $transaction_type_id }}">
                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="submitForm()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                fetch('/api/categories')
                    .then(response => response.json())
                    .then(categories => {
                        const categorySelect = document.getElementById('category');

                        categories.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            categorySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error al recuperar las categorías:', error));
                    
                fetch('/api/transactions?transaction_type_id={{ $transaction_type_id }}')
                    .then(response => response.json())
                    .then(transactions => showTableData(transactions))
                    .catch(error => console.error('Error al recuperar las transacciones:', error));

                function submitForm() {
                    const form = document.getElementById('addTransactionForm');
                    const formData = new FormData(form);
                    fetch('/api/transactions', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(transaction => {
                        // Recargar la tabla de ingresos para mostrar el nuevo ingreso
                        fetchTransactionsByTransactionType('{{ $transaction_type_id }}');
                    })
                    .catch(error => console.error('Error al ingresar el ingreso:', error));
                    hideModal();
                }

                function hideModal() {
                    document.getElementById('addTransactionModal').classList.remove('show');
                    document.getElementById('addTransactionModal').style.display = 'none';
                    document.querySelector('.modal-backdrop').remove();
                }

                // Función para obtener las transacciones basadas en la categoría seleccionada
                function fetchTransactionsByTransactionType(transaction_type_id) {
                    fetch(`/api/transactions/filter/${transaction_type_id}`)
                        .then(response => response.json())
                        .then(transactions => showTableData(transactions))
                        .catch(error => console.error('Error al recuperar los datos:', error));
                }

                function showTableData(transactions) {
                    transactions.sort((a, b) => new Date(b.date) - new Date(a.date));
                    const tableBody = document.getElementById('transactions-table').getElementsByTagName('tbody')[0];

                    transactions.forEach(transaction => {
                        const row = tableBody.insertRow();
                        row.innerHTML = `
                            <td>${transaction.date}</td>
                            <td>$${transaction.amount}</td>
                            <td>${transaction.description}</td>
                            <td>${transaction.category.name}</td>
                        `;
                    });
                }
            </script>

            <style>
                .table-container {
                    max-height: 500px; /* Altura máxima del contenedor */
                    overflow-y: auto; /* Habilita el desplazamiento vertical */
                }
            </style>
            @endsection
        </div>    
    </div>
</x-app-layout>
