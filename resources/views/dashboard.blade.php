@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <section class="content">

        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalInvoices }}</h3>

                            <p>Total Invoices</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('invoice.index') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $client }}</h3>

                            <p>Total Clients</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('client.index') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ currency($totalRevenue) }}</h3>
                            <p>Total Revenue</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('invoice.index') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ currency($revenueThisMonth) }}</h3>
                            <p>Revenue This Month</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-6 connectedSortable">

                    <!-- Map card -->
                    <div class="card bg-gradient-light">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                Latest Invoices
                            </h3>
                            <!-- card tools -->
                            <div class="card-tools">
                                <button type="button" class="btn btn-dark btn-sm" data-card-widget="collapse"
                                    title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Client</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestInvoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->client->name ?? 'N/A' }}</td>
                                            <td>{{ currency($invoice->total) }}</td>
                                            <td>{{ $invoice->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No invoices found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </section>
                <!-- right col -->

                <section class="col-lg-6 connectedSortable">

                    <!-- Map card -->
                    <div class="card bg-gradient-light">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                Top Clients
                            </h3>
                            <!-- card tools -->
                            <div class="card-tools">
                                <button type="button" class="btn btn-dark btn-sm" data-card-widget="collapse"
                                    title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Client Name</th>
                                        <th>Total Invoice Amount</th>
                                        <th>Invoice Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topClients as $index => $clientData)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $clientData->client->name ?? 'N/A' }}</td>
                                            <td>{{ currency($clientData->total_amount) }}</td>
                                            <td>{{ $clientData->invoice_count }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No top clients found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </section>
                <!-- Left col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->

    </section>
    <!-- /.content -->

    <div class="col-md-12">
        <div class="card shadow mb-4 h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Payment Overview</h6>
            </div>
            <div class="card-body">
                <canvas id="invoiceChart"></canvas>
            </div>
        </div>
    </div>

@endsection

@push('footer')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('invoiceChart').getContext('2d');
        const invoiceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                        label: 'Paid Amount',
                        data: {!! json_encode($paidData) !!},
                        backgroundColor: 'rgba(78, 115, 223, 0.7)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Due Amount',
                        data: {!! json_encode($dueData) !!},
                        backgroundColor: 'rgba(231, 74, 59, 0.7)',
                        borderColor: 'rgba(231, 74, 59, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        console.log(document.getElementById('invoiceChart'));
    </script>
@endpush
