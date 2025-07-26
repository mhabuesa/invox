@extends('layouts.app')
@section('title', 'Invoices')
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Invoice Payment</h1>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-file-invoice mr-2 text-white-50"></i>Invoice
                </a>
            </div>

            <!-- Stats Row -->
            <div class="row mb-4">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card stat-card total h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Amount</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ currency($invoice->total) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <span class="currencyIcon"> {{ currency() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card stat-card paid h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Paid</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ currency($invoice->payment->sum('amount')) ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <span class="currencyIcon"> {{ currency() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card stat-card due h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Due</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ currency($invoice->total - $invoice->payment->sum('amount')) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <span class="currencyIcon"> {{ currency() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Payment Form Column -->
                @if ($invoice->total - $invoice->payment->sum('amount') > 0)
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Make Payment</h6>
                            </div>
                            <div class="card-body">
                                <form id="paymentForm" action="{{ route('invoice.payment.store', $invoice->id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="amount">Payment Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ currency() }}</span>
                                            <input type="number" class="form-control" id="amount" placeholder="0.00"
                                                step="0.01" min="0" required name="amount">
                                        </div>
                                        @if (session('error'))
                                            <div class="text-danger font-weight-bold">
                                                {{ session('error') }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="btn btn-light mb-3 p-3 w-100 border border-light rounded cursor-pointer selected bg-dark bg-opacity-10 border-primary"
                                                    data-method="creditCard">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <i class="fas fa-credit-card fa-2x text-primary"></i>
                                                        </div>
                                                        <div class="mx-3">
                                                            <h6 class="mb-0">Credit Card</h6>
                                                            <small class="text-muted">Visa, Mastercard, Amex</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="btn btn-light mb-3 p-3 w-100 rounded"
                                                    data-method="bankTransfer">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <i class="fas fa-university fa-2x text-primary"></i>
                                                        </div>
                                                        <div class="mx-3">
                                                            <h6 class="mb-0">Bank Transfer</h6>
                                                            <small class="text-muted">Direct bank payment</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 m-auto">
                                                <div class="btn btn-light mb-3 p-3 w-100 rounded" data-method="cash">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <i class="fa fa-money-bill fa-2x text-primary"></i>
                                                        </div>
                                                        <div class="mx-3">
                                                            <h6 class="mb-0">Cash</h6>
                                                            <small class="text-muted">Pay with cash</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="paymentMethod" name="paymentMethod" value="creditCard">
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-paper-plane me-2"></i> Submit Payment
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Payment History Column -->
                <div class="col-lg-6 mb-4 ">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Payment History</h6>
                        </div>
                        <div class="card-body">

                            @forelse ($payments as $payment)
                                <div class="payment-history-item">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="font-weight-bold"><i class="far fa-calendar me-1"></i>
                                            {{ $payment->created_at->format('M d, Y') }}</h6>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted text-capitalize">{{ $payment->payment_method }}</span>
                                        <span class="font-weight-bold">{{ currency($payment->amount) }}</span>
                                    </div>
                                </div>
                                <hr>

                            @empty
                                <p class="text-muted">No payment history found.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('footer')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const methodCards = document.querySelectorAll('[data-method]');
            const paymentMethodInput = document.getElementById('paymentMethod');

            methodCards.forEach(card => {
                card.addEventListener('click', function() {
                    methodCards.forEach(c => {
                        c.classList.remove('selected', 'border-dark', 'bg-dark',
                            'bg-opacity-10');
                        c.classList.add('border-light');
                    });

                    this.classList.add('selected', 'border-dark', 'bg-dark', 'bg-opacity-10');
                    this.classList.remove('border-light');

                    paymentMethodInput.value = this.dataset.method;
                });
            });
        });
    </script>
@endpush
