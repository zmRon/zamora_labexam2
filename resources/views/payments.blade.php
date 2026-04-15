<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Payment Records</h2>
    </x-slot>

    <div class="page-container">
        <div class="page-inner">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="table-wrap">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Order #</th>
                                        <th>Rice</th>
                                        <th>Order Total</th>
                                        <th>Amount Paid</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->id }}</td>
                                            <td>
                                                <a href="{{ route('orders.show', $payment->order) }}" class="action-link">#{{ $payment->order_id }}</a>
                                            </td>
                                            <td class="font-medium">{{ $payment->order->product->name }}</td>
                                            <td>₱{{ number_format($payment->order->total_amount, 2) }}</td>
                                            <td class="font-semibold">₱{{ number_format($payment->amount_paid, 2) }}</td>
                                            <td>
                                                @if($payment->status === 'Paid')
                                                    <span class="badge badge-paid">Paid</span>
                                                @else
                                                    <span class="badge badge-unpaid">Unpaid</span>
                                                @endif
                                            </td>
                                            <td>{{ $payment->updated_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($payment->status === 'Unpaid')
                                                    <form method="POST" action="{{ route('payments.pay', $payment) }}" class="inline-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-link" style="border:none;background:none;cursor:pointer;color:#16a34a;font-weight:500;">Mark Paid</button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('payments.unpay', $payment) }}" class="inline-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-delete">Mark Unpaid</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="empty-state">No payments found. They'll show up once orders are placed.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
