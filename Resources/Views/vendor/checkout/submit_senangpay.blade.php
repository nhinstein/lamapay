<html>
    <head>
    <title>senangPay proccess</title>
    </head>
    <body onload="document.order.submit()">
        <h3>Proccessing...</h3>
        <form name="order" action="{{ senangpay_url() }}" method="POST">
            <input type="hidden" name="detail" value="{{ $detail }}">
            <input type="hidden" name="amount" value="{{ $data->amount }}">
            <input type="hidden" name="order_id" value="{{ $data->transaction_number }}">
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email }}">
            <input type="hidden" name="phone" value="{{ $user->phone }}">
            <input type="hidden" name="hash" value="{{ senangpay_hasing($detail, $data->amount, $data->transaction_number) }}">
          </form>
    </body>
  </html>
