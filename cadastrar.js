document.addEventListener('DOMContentLoaded', () => {
    const userTypeRadios = document.querySelectorAll('input[name="userType"]');
    const formPai = document.getElementById('formPai');
    const formBaba = document.getElementById('formBaba');

    userTypeRadios.forEach(radio => {
        radio.addEventListener('change', (event) => {
            switch (event.target.value) {
                case 'pai':
                    formPai.classList.remove('hidden');
                    formBaba.classList.add('hidden');
                    formPai.querySelectorAll('input').forEach(input => input.required = true);
                    formBaba.querySelectorAll('input').forEach(input => input.required = false);
                    break;
                case 'baba':
                    formPai.classList.add('hidden');
                    formBaba.classList.remove('hidden');
                    formBaba.querySelectorAll('input').forEach(
                        input => input.type == 'checkbox' ? input.required = false : input.required = true
                    );
                    formPai.querySelectorAll('input').forEach(input => input.required = false);
                    break;
                default:
                    formPai.classList.add('hidden');
                    formBaba.classList.add('hidden');
                    formBaba.querySelectorAll('input').forEach(input => input.required = true);
                    formPai.querySelectorAll('input').forEach(input => input.required = true);
                    break;
            }
        });
    });

    // Trigger the change event on the checked radio button to set the initial state
    const checkedRadio = document.querySelector('input[name="userType"]:checked');
    if (checkedRadio) {
        checkedRadio.dispatchEvent(new Event('change'));
    }
});


const form = document.getElementById('formUsuario');
const campos = document.querySelectorAll('.required');
const emailRegex = /^[a-zA-Z0-9.!#$%&'+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)$/;
const spans = document.querySelectorAll('.span-required');
        function nameValidate(){
            if (campos[0].value.length < 3)
            {
                setError(0);
            }
            else
            {
                removeError(0);
            }
        }

        function validarData() {
            var dataInput = document.getElementById('data').value;
            var dataAtual = new Date().toISOString().slice(0, 10);

            if (dataInput >= dataAtual) {
                setError(2);
                document.getElementById('data').value = '';
            }else{
                removeError(2);
            }

        }

        function emailValidate(){
            if(!emailRegex.test(campos[8].value)){
                setError(8);
            }else{
                removeError(8);

        }
        }

        function setError(index){
            campos[index].style.border = '1px solid #FF0000';
            spans[index].style.display = "block";

        }

        function removeError(index){
            campos[index].style.border = '';
            spans[index].style.display = "none";

        }
