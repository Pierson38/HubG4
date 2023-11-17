document.addEventListener("DOMContentLoaded", () => {
    /* let chatDiv = document.querySelector('.overflow-auto');
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
    if (convId != null) {
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
                        console.log($('.last-chat'));
                        $('.last-chat').forEach(element => {
                            element.classList.remove('last-chat');
                        });
                        $('.chat-conversation .simplebar-content').append(responseData);
                        //recuperrer element avec .simplebar-content

                        var maDiv = document.querySelector('.chat-conversation .simplebar-content-wrapper');
                        $('.chat-conversation .simplebar-content').css('height', "auto")
                        maDiv.scrollTop = maDiv.scrollHeight;

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
    }

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
                    $('.chat-conversation .simplebar-content').css('height', "auto")
                    //recuperrer element avec .simplebar-content
                    var maDiv = document.querySelector('.chat-conversation .simplebar-content-wrapper');
                    maDiv.scrollTop = maDiv.scrollHeight;
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

    let imageForm = document.getElementById('addImageMessageForm');
    function handleImageMessageForm(event) {
        event.preventDefault(); // Empêche la page de se rafraîchir après le submit du formulaire

        console.log(event)

        let image = document.getElementById('addImageMessageInput').files[0];

        const data = new FormData();
        data.append('image', image);

        $.ajax({
            url: 'https://127.0.0.1:8000/conversation/' + convId + '/add-image-message',
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            headers: {
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
            }
        }).done(function (responseData) {
            console.log(responseData);
            $.ajax({
                url: 'https://127.0.0.1:8000/conversation-utils/getTemplateSelfMessage/' + responseData,
                method: 'GET',
                headers: {
                    "Access-Control-Allow-Origin": "*",
                    "Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS"
                }
            }).done(function (responseDataa) {

                $('#addImageMessage').modal('hide');
                $('.chat-conversation .simplebar-content').append(responseDataa);
                $('.chat-conversation .simplebar-content').css('height', "auto")
                //recuperrer element avec .simplebar-content
                var maDiv = document.querySelector('.chat-conversation .simplebar-content-wrapper');
                maDiv.scrollTop = maDiv.scrollHeight;
                // let chatDiv = document.querySelector('.simplebar-content-wrapper');
                // keepScrolledToBottom(chatDiv);

            }).fail(function (error) {
                console.log(error);
            });

        }).fail(function (error) {
            console.log(error);
        });

    }
    imageForm.addEventListener('submit', handleImageMessageForm);

    var maDiv = document.querySelector('.chat-conversation .simplebar-content-wrapper');
    maDiv.scrollTop = maDiv.scrollHeight;

    if ($('.chat-conversation .simplebar-content').children().length == 0) {
        $('.chat-conversation .simplebar-content').css('height', "8em")
    }
});
