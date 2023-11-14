document.addEventListener("DOMContentLoaded", () => {
   /*  let chatDiv = document.querySelector('.overflow-auto');
    chatDiv.scrollTop = chatDiv.scrollHeight; // On souhaite scroller toujours jusqu'au dernier message du chat */

    let form = document.getElementById('inputMessageForm');
    function handleForm(event) {
        event.preventDefault(); // Empêche la page de se rafraîchir après le submit du formulaire
    }
    form.addEventListener('submit', handleForm);

    function keepScrolledToBottom(element) {
        const isScrolledToBottom = element.scrollHeight - element.clientHeight <= element.scrollTop + 1;
    
        if (isScrolledToBottom) {
            element.scrollTop = element.scrollHeight;
        }
    }
    //Faire requete ajax pour récupérer les messages de la conversation

    //Faire fonction qui dure s'appel toute les secondes
    setInterval(function () {
        $.ajax({
            url: '/conversation/' + convId + '/get-messages',
            method: 'GET',
            headers: {
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
            }
        }).done(function (responseData) {

            responseData.forEach(element => {
                element = JSON.parse(element);
                $.ajax({
                    url: 'https://127.0.0.1:8000/conversation-utils/getTemplateSelfMessage/' + element.id,
                    method: 'GET',
                    headers: {
                        "Access-Control-Allow-Origin": "*",
                        "Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
                    }
                }).done(function (responseData) {
        
                    $('.chat-conversation .simplebar-content').append(responseData);
                    //recuperrer element avec .simplebar-content

                    // let chatDiv = document.querySelector('.simplebar-content-wrapper');
                    // keepScrolledToBottom(chatDiv);
        
                }).fail(function (error) {
                    console.log(error);
                });
            });
            
            // $('#messages').append(responseData);
    
        }).fail(function (error) {
            console.log(error);
        });
    
    }, 1000);


   /*  eventSource.onmessage = function (e) {
        console.log();
        let data = JSON.parse(e.data);

        console.log(data);
        console.log(userId);
        console.log(userId == data.userAccount.id);
        $.ajax({
            url: 'https://127.0.0.1:8000/conversation-utils/getTemplateSelfMessage',
            method: 'POST',
            data: {
                'message': data.message,
                'self': userId == data.userAccount.id
            },
            headers: {
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
            }
        }).done(function (responseData) {
            console.log(responseData);

            $('#messages').append(responseData);

        }).fail(function (error) {
            console.log(error);
        });
    }*/

    const submit = document.getElementById('inputMessageButton');
    submit.addEventListener("click", async () => { // On change le comportement du submit
        const message = document.getElementById('inputMessage'); // Récupération du message dans l'input correspondant
        if (message.value.length > 0) {
            const data = { // La variable data sera envoyée au controller
                'content': message.value, // On transmet le message...
                'userId': userId
            }

            $.ajax({
                url: 'https://127.0.0.1:8000/conversation/' + convId + '/add-message',
                method: 'POST',
                data: data,
                headers: {
                    "Access-Control-Allow-Origin": "*",
                    "Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
                }
            }).done(function (responseData) {
                $.ajax({
                    url: 'https://127.0.0.1:8000/conversation-utils/getTemplateSelfMessage/' + responseData,
                    method: 'GET',
                    headers: {
                        "Access-Control-Allow-Origin": "*",
                        "Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
                    }
                }).done(function (responseData) {
        
                    $('.chat-conversation .simplebar-content').append(responseData);
                    //recuperrer element avec .simplebar-content

                    // let chatDiv = document.querySelector('.simplebar-content-wrapper');
                    // keepScrolledToBottom(chatDiv);
        
                }).fail(function (error) {
                    console.log(error);
                });

            }).fail(function (error) {
                console.log(error);
            });


            message.value = '';
        }
    }); 

});