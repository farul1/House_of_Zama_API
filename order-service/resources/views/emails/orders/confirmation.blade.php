<x-mail::message>
# 🎉 Order Confirmation

Hi <strong>{{ $order->client->name ?? 'Customer' }}</strong>,

Thank you for placing an order with <strong>{{ config('app.name') }}</strong>!

---

**🧾 Order ID:** {{ $order->id }}
**📝 Notes:** {{ $order->catatan ?? 'No additional notes provided.' }}

---

We are excited to help you with your session. Our team will reach out shortly to confirm the details.

<x-mail::button :url="url('/orders/' . $order->id)" color="success">
🔍 View Your Order
</x-mail::button>

If you have any questions, feel free to reply to this email.

Thanks again,
<strong>{{ config('app.name') }}</strong> Team
</x-mail::message>
