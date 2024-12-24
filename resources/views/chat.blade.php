<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Store GPT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #chat-form {
            position: sticky;
            bottom: 0;
            background-color: white;
            z-index: 10; /* Ensure it stays above other elements */
        }
    </style>
</head>
<body>

<div class="container mx-auto p-4 bg-gray-100 min-h-screen flex flex-col">
    <h1 class="text-2xl font-bold mb-4 text-center text-gray-800">Store Chat with GPT</h1>
    
    <div id="chat-box" class="flex-1 overflow-y-auto bg-white shadow-md rounded-lg p-4 space-y-4">
        <!-- Display existing conversations -->
        @foreach ($conversations as $conversation)
            <div class="message">
                <div class="mb-2">
                    <strong class="text-blue-600">User:</strong>
                    <span class="text-gray-700">{{ $conversation->message }}</span>
                </div>
                <div>
                    <strong class="text-green-600">ChatGPT:</strong>
                    <span class="text-gray-700">{!! $conversation->response !!}</span>
                                </div>
            </div>
        @endforeach
    </div>

    <form id="chat-form" class="mt-4 flex items-center space-x-2">
        @csrf
        <textarea 
            id="user-message" 
            name="message" 
            rows="2" 
            class="flex-1 p-2 border rounded-lg focus:ring focus:ring-blue-300 outline-none resize-none" 
            placeholder="Type your message here...">
        </textarea>
        <button 
            type="button" 
            id="send-button" 
            class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">
            Send
        </button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#send-button').on('click', function () {
            const message = $('#user-message').val().trim();
            if (message === '') {
                alert('Message cannot be empty');
                return;
            }

            // Clear the input field
            $('#user-message').val(''); 

            // Append the user's message to the chat UI
            $('#chat-box').append(`
                <div class="message">
                    <div class="mb-2">
                        <strong class="text-blue-600">User:</strong>
                        <span class="text-gray-700">${message}</span>
                    </div>
                </div>
            `);

            // Send the message to the server via AJAX
            $.ajax({
                url: "{{ route('chat.send') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    message: message,
                },
                success: function (response) {
                    var parsedResponse = JSON.parse(response);

                    // Append ChatGPT's response to the chat UI
                    $('#chat-box').append(`   
                        <div class="message">
                            <div>
                                <strong class="text-green-600">ChatGPT:</strong>
                                <span class="text-gray-700"></span>
                            </div>
                        </div>
                    `);

                    $('#chat-box .message:last-child .text-gray-700').html(parsedResponse.response);

                    // Scroll to the latest message
                    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                },
                error: function (xhr) { 
                    console.error('Error:', xhr.responseText);
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    });
</script>

</body>
</html>
