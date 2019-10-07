function docReady(fn) {
    // see if DOM is already available
    if (document.readyState === "complete" || document.readyState === "interactive") {
        // call on next available tick
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}    

docReady(function() {
var elements = stripe.elements();
var style = {
  base: {
    iconColor: '#666EE8',
    color: '#31325F',
    lineHeight: '40px',
    fontWeight: 300,
    fontFamily: 'Helvetica Neue',
    fontSize: '15px',

    '::placeholder': {
      color: '#CFD7E0',
    },
  },
};
var card = elements.create('cardNumber', {
style: style
  });
card.mount('#card-element');
var cardCvcElement = elements.create('cardCvc', {
  style: style
});
cardCvcElement.mount('#card-cvc-element');

var cardExpiryElement = elements.create('cardExpiry', {
  style: style
});
cardExpiryElement.mount('#card-expiry-element');

function setOutcome(result) {
  var successElement = document.querySelector('.success');
  var errorElement = document.querySelector('.error');
  successElement.classList.remove('visible');
  errorElement.classList.remove('visible');

  if (result.token) {
    //successElement.querySelector('.token').textContent = result.token.id;
    successElement.classList.add('visible');

    var amount = document.getElementById('amount').value.replace(/\./g, '');
   
    var formData = new FormData();  
    formData.append("token", result.token.id); 
    formData.append("amount", amount);
    formData.append("note", document.getElementById("note").value);


    var xhr = new XMLHttpRequest();  
    xhr.responseType = 'json';
    xhr.open("POST", location.href, true);  
    xhr.send(formData); 
        xhr.onload  = function() {
                var jsonResponse = xhr.response;

		if(jsonResponse == null) {
                        errorElement.textContent = "Unknown Server Error";
                        successElement.classList.remove('visible');
                        errorElement.classList.add('visible');
                        unlock_inputs();
		} else {
			if (jsonResponse.success == false) {
				errorElement.textContent = jsonResponse.error;
  				successElement.classList.remove('visible');
  				errorElement.classList.add('visible');
				unlock_inputs();
			} else {
				document.getElementById("main").innerHTML = "<div class=\"group\"><label><span>SUCCESS</span>$" + jsonResponse['amount'] + " is making its way to our bank account </label><label><span>Transaction ID</span> " + jsonResponse['id'] + "</label><label><span>Again?</span><a href=\"\" onclick=\"document.reload\">Another Transaction</a><br /></label></div>";
			}
		}
        };  
  } else if (result.error) {
    unlock_inputs();
    errorElement.textContent = result.error.message;
    errorElement.classList.add('visible');
  }
}

card.on('change', function(event) {
  setOutcome(event);
});

document.getElementsByName('amount')[0].addEventListener('keydown', function (evt) {
  amount = document.getElementById("amount").value;
  var errorElement = document.querySelector('.error');
  if (amount == "") {
    errorElement.textContent = "Please enter a valid amount";
    errorElement.classList.add('visible');
  } else {
    errorElement.classList.remove('visible');
  }
 });
document.querySelector('form').addEventListener('submit', function(e) {
  e.preventDefault();

  var errorElement = document.querySelector('.error');
  amount = document.getElementById("amount").value;
  if (amount == "") {
    unlock_inputs();
    errorElement.textContent = "Please enter a valid amount";
    errorElement.classList.add('visible');
    return false;
  }


  var options = {
    address_zip: document.getElementById('postal_code').value,
    cvc: document.getElementById('card-cvc-element').value
  };
  lock_inputs();
  stripe.createToken(card, options).then(setOutcome);
});

function lock_inputs(){
var inputs = document.getElementsByClassName("field"); 
for (var i = 0; i < inputs.length; i++) { 
    inputs[i].disabled = true;
}
    document.getElementById("submit_button").disabled = true; 
}

function unlock_inputs(){
var inputs = document.getElementsByClassName("field"); 
for (var i = 0; i < inputs.length; i++) { 
    inputs[i].disabled = false;
}
    document.getElementById("submit_button").disabled = false; 
}

});   
