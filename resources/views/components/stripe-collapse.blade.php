{{-- <h5 class="mt-3">it is redirect to stripe checkout page</h5>
<div class="form-group">
    <div class="row">
        <div class="col-lg-8">
            <input class="form-controls w-100" type="text" name="card_number" id="card_number" placeholder="card number" />
        </div>
        <div class="col-lg-4">
            <input class="form-controls w-100" type="date" name="date" id="date" />
        </div>
    </div>
</div> --}}

@push('styles')
<style type="text/css">
    .StripeElement {
      box-sizing: border-box;

      height: 40px;

      padding: 10px 12px;

      border: 1px solid transparent;
      border-radius: 4px;
      background-color: white;

      box-shadow: 0 1px 3px 0 #e6ebf1;
      -webkit-transition: box-shadow 150ms ease;
      transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
      box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
      border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
      background-color: #fefde5 !important;
    }
</style>
@endpush
<label class="mt-3" for="card-element">
    Card details:
</label>

<div id="cardElement"></div>

<small class="form-text text-muted" id="cardErrors" role="alert"></small>

<input type="hidden" name="payment_method" id="paymentMethod">

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>

<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');

    const elements = stripe.elements({ locale: 'en' });
    const cardElement = elements.create('card');

    cardElement.mount('#cardElement');
</script>

<script>
    const form= document.getElementById('paymentForm');
    const btn = document.getElementById('paybutton');

    paybutton.addEventListener('click', async(e) => {
        if (form.elements.payment_platform.value === "{{ $item->id }}") {
            console.log('ok');
        e.preventDefault();
        const { paymentMethod, error } = await stripe.createPaymentMethod(
                'card', cardElement, {
                    billing_details: {
                        "name": "{{ auth()->user()->name }}",
                        "email": "{{ auth()->user()->email }}"
                    }
                }
            );
            if (error) {
                const displayError = document.getElementById('cardErrors');
                displayError.textContent = error.message;
            } else {
                const tokenInput = document.getElementById('paymentMethod');
                tokenInput.value = paymentMethod.id;
                form.submit();
            }
        }
    });
</script>

@endpush