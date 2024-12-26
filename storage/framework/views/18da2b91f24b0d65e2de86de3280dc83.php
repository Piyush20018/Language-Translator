<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Translator with Voice Input</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e2eafc, #dff6fd);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #micButton {
            border: none;
            background-color: transparent;
            cursor: pointer;
            font-size: 1.3rem;
            color: #007bff;
            bottom: 10px;
            left: 10px;
            position: absolute;
        }

        #micButton:focus {
            outline: none;
        }

        #micButton.active {
            color: #dc3545;
            animation: micGlow 1.5s infinite;
            transform: scale(1.2);
        }

        @keyframes micGlow {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
            }

            70% {
                box-shadow: 0 0 30px 10px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .position-relative {
            position: relative;
        }

        #text {
            padding-left: 50px;
        }

        textarea,
        select {
            border: 2px solid #007bff;
            border-radius: 8px;
        }

        textarea:focus,
        select:focus {
            border-color: #0056b3;
            box-shadow: 0px 0px 8px rgba(0, 123, 255, 0.5);
        }

        .bg-custom {
            background-color: #ffffff;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-right: 5px;

        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            margin-bottom: -16px;
            background-color: #e2495c;
        }

        .btn-secondary:hover {
            background-color: #dd1043;
        }

        h2 {
            color: #004085;
            font-weight: bold;
        }

        p#translatedText {
            font-size: 1.2rem;
            color: #495057;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h2 class="text-center mb-5">English ↔ Hindi Translator with Voice Input</h2>
        <div class="row justify-content-center">
            <!-- Input Section -->
            <div class="col-md-5 bg-custom p-4 mb-4">
                <h4 class="text-primary">Input</h4>
                <form id="translateForm">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="source_lang" class="form-label">Source Language:</label>
                        <select name="source_lang" id="source_lang" class="form-control" required>
                            <option value="en">English</option>
                            <option value="hi">Hindi</option>
                            <option value="fr">French</option>
                            <option value="gu">Gujarati</option>
                            <option value="es">Spanish</option>
                            <option value="de">German</option>
                            <option value="it">Italian</option>
                            <option value="ja">Japanese</option>
                            <option value="pt">Portuguese</option>
                            <option value="ru">Russian</option>
                            <option value="zh">Chinese</option>
                            <option value="ar">Arabic</option>
                            <option value="ko">Korean</option>
                            <option value="pl">Polish</option>
                            <option value="nl">Dutch</option>
                            <option value="bn">Bengali</option>
                            <option value="mr">Marathi</option>
                            <option value="ta">Tamil</option>
                            <option value="te">Telugu</option>
                            <option value="ml">Malayalam</option>
                            <option value="kn">Kannada</option>
                            <option value="pa">Punjabi</option>
                            <option value="sw">Swahili</option>
                            <option value="tr">Turkish</option>
                            <option value="cs">Czech</option>
                            <option value="da">Danish</option>
                            <option value="el">Greek</option>
                            <option value="th">Thai</option>
                            <option value="vi">Vietnamese</option>
                            <option value="he">Hebrew</option>
                            <option value="id">Indonesian</option>
                            <option value="ms">Malay</option>
                            <option value="ro">Romanian</option>
                            <option value="sr">Serbian</option>
                            <option value="sk">Slovak</option>
                            <option value="uk">Ukrainian</option>
                            <option value="hu">Hungarian</option>
                            <option value="ca">Catalan</option>
                            <option value="hr">Croatian</option>
                            <option value="lt">Lithuanian</option>
                            <option value="lv">Latvian</option>
                            <option value="et">Estonian</option>
                            <option value="sq">Albanian</option>
                            <option value="sl">Slovenian</option>
                            <option value="no">Norwegian</option>
                            <option value="bg">Bulgarian</option>
                            <option value="mt">Maltese</option>
                            <option value="fa">Persian</option>
                            <option value="ne">Nepali</option>
                        </select>
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="text" class="form-label">
                            <h5>Enter Text:</h5>
                        </label>
                        <textarea name="text" id="text" class="form-control" rows="6"
                            placeholder="Type or speak here..." required></textarea>
                        <button type="button" id="micButton" title="Speak">
                            🎤
                        </button>
                    </div>
                    <div class="character-counter" id="charCount">Characters: 0 | Words: 0 | Paragraphs: 0</div>
                </form>
            </div>

            <!-- Translation Section -->
            <div class="col-md-5 bg-custom p-4 mb-4">
                <h4 class="text-primary">Translation</h4>
                <div class="mb-3">
                    <label for="target_lang" class="form-label">Target Language:</label>
                    <select name="target_lang" id="target_lang" class="form-control" required>
                        <option value="hi">Hindi</option>
                        <option value="en">English</option>
                        <option value="fr">French</option>
                        <option value="gu">Gujarati</option>
                        <option value="es">Spanish</option>
                        <option value="de">German</option>
                        <option value="it">Italian</option>
                        <option value="ja">Japanese</option>
                        <option value="pt">Portuguese</option>
                        <option value="ru">Russian</option>
                        <option value="zh">Chinese</option>
                        <option value="ar">Arabic</option>
                        <option value="ko">Korean</option>
                        <option value="pl">Polish</option>
                        <option value="nl">Dutch</option>
                        <option value="bn">Bengali</option>
                        <option value="mr">Marathi</option>
                        <option value="ta">Tamil</option>
                        <option value="te">Telugu</option>
                        <option value="ml">Malayalam</option>
                        <option value="kn">Kannada</option>
                        <option value="pa">Punjabi</option>
                        <option value="sw">Swahili</option>
                        <option value="tr">Turkish</option>
                        <option value="cs">Czech</option>
                        <option value="da">Danish</option>
                        <option value="el">Greek</option>
                        <option value="th">Thai</option>
                        <option value="vi">Vietnamese</option>
                        <option value="he">Hebrew</option>
                        <option value="id">Indonesian</option>
                        <option value="ms">Malay</option>
                        <option value="ro">Romanian</option>
                        <option value="sr">Serbian</option>
                        <option value="sk">Slovak</option>
                        <option value="uk">Ukrainian</option>
                        <option value="hu">Hungarian</option>
                        <option value="ca">Catalan</option>
                        <option value="hr">Croatian</option>
                        <option value="lt">Lithuanian</option>
                        <option value="lv">Latvian</option>
                        <option value="et">Estonian</option>
                        <option value="sq">Albanian</option>
                        <option value="sl">Slovenian</option>
                        <option value="no">Norwegian</option>
                        <option value="bg">Bulgarian</option>
                        <option value="mt">Maltese</option>
                        <option value="fa">Persian</option>
                        <option value="ne">Nepali</option>
                    </select>
                </div>

                <h5>Output:</h5>
                <div class="bg-light p-3 border rounded" style="min-height: 200px;">

                    <p id="translatedText" class="text-secondary">The translation will appear here...</p>

                </div>
                <button id="speakButton" class="btn btn-primary mt-3">🔊 Listen</button>
                <button id="copyButton" class="btn btn-secondary">
                    <i class="fas fa-copy"></i> Copy
                </button>
                <span id="copyMessage" class="text-success ms-2" style="display: none;">Copied!</span>

            </div>
        </div>


    </div>

    <script>
        $(document).ready(function () {
            // Typing Debounce
            let typingTimer;
            const debounceTime = 300;

            // Function to translate text
            function translateText() {
                const formData = {
                    _token: "<?php echo e(csrf_token()); ?>",
                    text: $('#text').val(),
                    source_lang: $('#source_lang').val(),
                    target_lang: $('#target_lang').val()
                };

                if (formData.text.trim()) {
                    $.ajax({
                        url: "<?php echo e(route('translate.translate')); ?>",
                        method: 'POST',
                        data: formData,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#translatedText').text('Translating...');
                        },
                        success: function (response) {
                            if (response.success) {
                                const translatedText = response.translated;

                                // Split the input text and translated text into paragraphs
                                const inputParagraphs = formData.text.split(/\n\s*\n/); // Split by paragraph
                                const translatedParagraphs = translatedText.split(/\n\s*\n/); // Split by paragraph

                                // If the number of paragraphs is unequal, we adjust
                                while (translatedParagraphs.length < inputParagraphs.length) {
                                    translatedParagraphs.push(""); // Adding empty paragraphs to match the input
                                }

                                // Ensure that paragraphs are combined properly for output
                                let combinedTranslation = translatedParagraphs.map((para, index) => {
                                    return `<p>${para}</p>`;
                                }).join('\n');

                                $('#translatedText').html(combinedTranslation); // Use html() to preserve paragraphs

                                $('#speakButton').prop('disabled', false);
                            } else {
                                $('#translatedText').text('Translation failed.');
                                $('#speakButton').prop('disabled', true);
                            }
                        },
                        error: function () {
                            $('#translatedText').text('An error occurred.');
                            $('#speakButton').prop('disabled', true);
                        }
                    });
                } else {
                    $('#translatedText').text('The translation will appear here...');
                    $('#speakButton').prop('disabled', true);
                }
            }

            // Update Translation on Input

            $('#text').on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(translateText, debounceTime);

                // Update Character, Word, and Paragraph Counter
                const text = $(this).val();
                const charCount = text.length;
                const wordCount = text.trim() ? text.trim().split(/\s+/).length : 0;
                const paragraphCount = text.trim() ? text.trim().split(/\n\s*\n|\r\n\s*\r\n/).length : 0;
                $('#charCount').text(`Characters: ${charCount} | Words: ${wordCount} | Paragraphs: ${paragraphCount}`);
            });


            $('#source_lang, #target_lang').on('change', translateText);

            // Voice Input
            try {
                const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                recognition.lang = "en-US";
                recognition.interimResults = false;

                $('#micButton').click(function () {
                    const micButton = $(this);
                    micButton.toggleClass('active'); // Add animation class
                    recognition.start();
                });

                recognition.onstart = function () {
                    $('#micButton').addClass('active');
                };

                recognition.onresult = function (event) {
                    const spokenText = event.results[0][0].transcript;
                    $('#text').val(spokenText);
                    translateText();
                    $('#micButton').removeClass('active'); // Remove animation class
                };

                recognition.onerror = function () {
                    alert('Voice input error.');
                    $('#micButton').removeClass('active'); // Remove animation class
                };

                recognition.onend = function () {
                    $('#micButton').removeClass('active'); // Remove animation class if recognition stops
                };
            } catch (e) {
                $('#micButton').prop('disabled', true).attr('title', 'Speech recognition not supported.');
            }

            // Text-to-Speech
            $('#speakButton').click(function () {
                const text = $('#translatedText').text();
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = $('#target_lang').val();
                window.speechSynthesis.speak(utterance);
            });

            // Copy Text
            $('#copyButton').click(function () {
                const textToCopy = $('#translatedText').text();
                navigator.clipboard.writeText(textToCopy).then(() => {
                    // Show "Copied!" message
                    $('#copyMessage').fadeIn();

                    // Hide message after 2 seconds
                    setTimeout(() => {
                        $('#copyMessage').fadeOut();
                    }, 2000);
                }).catch(() => {
                    alert('Failed to copy text.'); // Fallback error message
                });
            });
        });

    </script>
</body>

</html><?php /**PATH C:\xampp\htdocs\Projects\translator\resources\views/translate.blade.php ENDPATH**/ ?>