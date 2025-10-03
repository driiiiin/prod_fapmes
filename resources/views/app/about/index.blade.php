<x-app-layout>
    <div class="content min-h-screen w-full bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8" style="margin-top: 20px;">

            <div class="flex items-center justify-center space-x-8 mb-12">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/DOH-logo.png') }}" class="h-32 w-auto" alt="DOH Logo">
                </div>
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/BP-logo.png') }}" class="h-40 w-auto" alt="Bagong Pilipinas Logo">
                </div>
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/WHO-logo.png') }}" class="h-40 w-auto" alt="WHO Logo">
                </div>
            </div>

            <!-- FAQs Section -->
            <div class="max-w-4xl mx-auto mb-24" style="margin-top: 50px;">
                <!-- <div class="max-w-4xl mx-auto mb-24" style="border-top: 5px solid transparent; border-image: linear-gradient(to right, #2563eb, #2dd4bf) 1;"> -->
                <div class="relative mb-16">
                    <h5 class="text-3xl font-black text-center text-gray-800" style="margin-top: 20px;">
                        Frequently Asked Questions (FAQs)
                    </h5>
                    <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-blue-600 to-teal-400 rounded-full"></div>
                </div>

                <!-- Language Selector for Text-to-Speech -->
                <div class="flex justify-end items-center mb-6 gap-3">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-teal-400 rounded-lg flex items-center justify-center shadow-sm">
                            <i class="fa fa-language text-white text-sm" aria-hidden="true"></i>
                        </div>
                        <span class="font-medium">Audio Language:</span>
                    </div>
                    <div class="relative">
                        <select id="tts-language" class="appearance-none bg-white pl-4 pr-10 py-2 border border-gray-200 rounded-lg shadow-sm hover:border-blue-400 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-200 text-sm text-gray-700 font-medium cursor-pointer">
                            <option value="en-US"> English (US)</option>
                            <option value="en-GB"> English (UK)</option>
                            <option value="en-AU"> English (Australia)</option>
                            <option value="es-ES"> Spanish (Spain)</option>
                            <option value="es-MX"> Spanish (Mexico)</option>
                            <option value="fr-FR"> French (France)</option>
                            <option value="fr-CA"> French (Canada)</option>
                            <option value="de-DE"> German (Germany)</option>
                            <option value="it-IT"> Italian (Italy)</option>
                            <option value="pt-BR"> Portuguese (Brazil)</option>
                            <option value="pt-PT"> Portuguese (Portugal)</option>
                            <option value="ru-RU"> Russian (Russia)</option>
                            <option value="ja-JP"> Japanese (Japan)</option>
                            <option value="ko-KR"> Korean (South Korea)</option>
                            <option value="zh-CN"> Chinese (Simplified)</option>
                            <option value="zh-TW"> Chinese (Traditional)</option>
                            <option value="ar-SA"> Arabic (Saudi Arabia)</option>
                            <option value="hi-IN"> Hindi (India)</option>
                            <option value="nl-NL"> Dutch (Netherlands)</option>
                            <option value="sv-SE"> Swedish (Sweden)</option>
                            <option value="no-NO"> Norwegian (Norway)</option>
                            <option value="da-DK"> Danish (Denmark)</option>
                            <option value="fi-FI"> Finnish (Finland)</option>
                            <option value="pl-PL"> Polish (Poland)</option>
                            <option value="tr-TR"> Turkish (Turkey)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <div id="translation-status" class="text-xs text-blue-600 hidden">
                        <i class="fa fa-spinner fa-spin mr-1"></i>
                        <span>Translating...</span>
                    </div>
                    <div id="translation-result" class="text-xs hidden">
                        <span id="translation-message"></span>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- <div class="transition-all duration-200 bg-white rounded-xl shadow-sm hover:shadow-md">
                        <button class="flex justify-between items-center w-full px-6 py-4 text-left" onclick="toggleFaq(this)">
                            <span class="text-lg font-medium text-gray-900">What is this system for?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="hidden px-6 pb-4 text-gray-600">
                            This system is designed to track and manage DOH Official Development Assistance / Foreign Assisted Projects.
                            <div class="flex justify-end mt-2">
                                <button onclick="speakText('What is this system for? This system is designed to track and manage D O H Official Development Assistance / Foreign Assisted Projects.')" class="text-blue-500 hover:underline"><i class="fa fa-volume-up" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div> -->

                    <div class="transition-all duration-200 bg-white rounded-xl shadow-sm hover:shadow-md">
                        <button class="flex justify-between items-center w-full px-6 py-4 text-left" onclick="toggleFaq(this)">
                            <span class="text-lg font-medium text-gray-900">How do I create a new project?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="hidden px-6 pb-4 text-gray-600">
                            To create a new project:
                            <div class="flex justify-end mt-2">
                                <button onclick="speakText('How do I create a new project? To create a new project: Navigate to Project Information Management in the side menu. Select Progress Data Update. Click the Add New Project button. Complete all required fields marked with red asterisk and other relevant details. Click Save Project to submit or to create your project. Click on the row of the desired project in the table to select it. This action will populate the Project ID and Project Name fields, allowing you to add further details using the additional tabs such as Implementation Schedule, Health Areas, Financial Accomplishments, and Physical Accomplishments. Please note: A project is considered selected when its row text appears in bold font. This indicates that the Project ID and Project Name have been successfully populated. Then Complete all required information in each tab. Also, Please take note that You can add multiple entries per project in Implementation Schedule, Health Areas, Financial Accomplishments & Physical Accomplishments tabs. ', this)" class="text-blue-500 hover:underline"><i class="fa fa-volume-up" aria-hidden="true"></i></button>
                            </div>
                            <div class="space-y-4 mt-4">
                                <div class="relative flex items-center space-x-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white">1</div>
                                    <p class="text-gray-700"> > Navigate to <span class="font-medium">Project Information Management</span> in the side menu</p>
                                </div>

                                <div class="relative flex items-center space-x-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white">2</div>
                                    <p class="text-gray-700"> > Select <span class="font-medium">Progress Data Update</span></p>
                                </div>

                                <div class="relative flex items-center space-x-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white">3</div>
                                    <p class="text-gray-700"> > Click the <span class="font-medium">Add New Project</span> button</p>
                                </div>

                                <div class="relative flex items-center space-x-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white">4</div>
                                    <p class="text-gray-700"> > Complete all required fields marked with red asterisk <span class="text-red-500 font-bold">*</span> and other relevant details</p>
                                </div>

                                <div class="relative flex items-center space-x-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white">5</div>
                                    <p class="text-gray-700"> > Click <span class="font-medium">Save Project</span> to submit or to create your project</p>
                                </div>

                                <div class="relative flex items-center space-x-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white">6</div>
                                    <p class="text-gray-700"> > Click on the row of the desired project in the table to select it. This action will populate the Project ID and Project Name fields, allowing you to add further details using the additional tabs such as Implementation Schedule, Health Areas, Financial Accomplishments, and Physical Accomplishments.<br><br>
                                    Please note: A project is considered selected when its row text appears in bold font. This indicates that the Project ID and Project Name have been successfully populated.</p>
                                </div>

                                <div class="relative flex items-center space-x-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white">7</div>
                                    <p class="text-gray-700"> > Complete all required information in each tab. Please take note that You can add multiple entries per project in Implementation Schedule, Health Areas, Financial Accomplishments & Physical Accomplishments tabs.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="transition-all duration-200 bg-white rounded-xl shadow-sm hover:shadow-md">
                        <button class="flex justify-between items-center w-full px-6 py-4 text-left" onclick="toggleFaq(this)">
                            <span class="text-lg font-medium text-gray-900">How can I track project progress?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="hidden px-6 pb-4 text-gray-600">
                            <p class="text-gray-700">You can monitor project progress at a glance through the <a href="/dashboard" class="font-medium text-blue-600 hover:underline">Dashboard</a>, which provides summary charts and key indicators.</p>
                            <p class="text-gray-700">For more detailed tracking, use the Implementation Schedule, Health Areas, Financial Accomplishments, and Physical Accomplishments tabs in each project's details page. These sections provide detailed insights into project milestones, budget utilization, and overall implementation status.</p>
                            <div class="flex justify-end mt-2">
                                <button onclick="speakText('How can I track project progress? You can monitor project progress at a glance through the Dashboard which provides summary charts and key indicators. For more detailed tracking use the Implementation Schedule Health Areas Financial Accomplishments and Physical Accomplishments tabs in each projects details page. These sections provide detailed insights into project milestones budget utilization and overall implementation status.', this)" class="text-blue-500 hover:underline"><i class="fa fa-volume-up" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="transition-all duration-200 bg-white rounded-xl shadow-sm hover:shadow-md">
                        <button class="flex justify-between items-center w-full px-6 py-4 text-left" onclick="toggleFaq(this)">
                            <span class="text-lg font-medium text-gray-900">Who can access this system?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="hidden px-6 pb-4 text-gray-600">
                            <p class="text-gray-700">Access is restricted to DOH - Bureau of International Health Cooperation (BIHC) and selected international health partners with current support to the Philippine Health Sector through the DOH.</p>
                            <div class="flex justify-end mt-2">
                                <button onclick="speakText('Who can access this system? Access is restricted to D O H - Bureau of International Health Cooperation (BIHC) and selected international health partners with current support to the Philippine Health Sector through the D O H.', this)" class="text-blue-500 hover:underline"><i class="fa fa-volume-up" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="transition-all duration-200 bg-white rounded-xl shadow-sm hover:shadow-md">
                        <button class="flex justify-between items-center w-full px-6 py-4 text-left" onclick="toggleFaq(this)">
                            <span class="text-lg font-medium text-gray-900">What is the scope of data that the system can generate?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="hidden px-6 pb-4 text-gray-600">
                            <p class="text-gray-700">from year 2024 onwards.</p>
                            <div class="flex justify-end mt-2">
                                <button onclick="speakText('What is the scope of data that the system can generate? from year 2024 onwards.', this)" class="text-blue-500 hover:underline"><i class="fa fa-volume-up" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="transition-all duration-200 bg-white rounded-xl shadow-sm hover:shadow-md">
                        <!-- <button class="flex justify-between items-center w-full px-6 py-4 text-left" onclick="toggleFaq(this)">
                            <span class="text-lg font-medium text-gray-900">Who can access this system?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="hidden px-6 pb-4 text-gray-600">
                            Access is restricted to authorized WHO and DOH personnel with valid login credentials. Each user is assigned specific roles and permissions based on their responsibilities within the organization.
                        </div> -->
                    </div>


                </div>
            </div>

            <!-- Contact Us Section -->
            <div class="max-w-4xl mx-auto" style="margin-top: 50px;">
                <!-- <div class="max-w-4xl mx-auto" style="margin-top: 100px; border-top: 5px solid transparent; border-image: linear-gradient(to right, #2563eb, #2dd4bf) 1;"> -->
                <div class="relative mb-16">
                    <h5 class="text-3xl font-black text-center text-gray-800" style="margin-top: 20px;">
                        Contact Us
                    </h5>
                    <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-blue-600 to-teal-400 rounded-full"></div>
                </div>

                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                    <div class="p-8">
                        <div class="flex flex-col md:flex-row gap-8">
                            <!-- Left Column -->
                            <div class="flex-1 space-y-6">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2 text-center">Department of Health</h3>
                                    <h4 class="text-lg font-semibold text-blue-600 mb-4 text-center">Bureau of International Health Cooperation</h4>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="bg-blue-100 p-2 rounded-lg mr-4">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-700">Location</p>
                                            <p class="text-gray-600">Building 3, Ground Floor, Department of Health, San Lazaro Compound, Rizal Avenue, Sta. Cruz, Manila</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="bg-blue-100 p-2 rounded-lg mr-4">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-700">Phone</p>
                                            <p class="text-gray-600">+63 2 8651 7800 local 1316 or 1339</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="bg-blue-100 p-2 rounded-lg mr-4">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-700">Email</p>
                                            <p class="text-gray-600">bihc@doh.gov.ph</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

                    <script>
                        let isSpeaking = false;
                        let selectedLanguage = 'en-US';
                        let availableVoices = [];
                        let isTranslating = false;
                        let translationStatusElement = null;
                        let translationResultElement = null;
                        let translationMessageElement = null;

                        // Language mapping for translation (LibreTranslate supported codes)
                        const languageMap = {
                            'en-US': 'en',
                            'en-GB': 'en',
                            'en-AU': 'en',
                            'es-ES': 'es',
                            'es-MX': 'es',
                            'fr-FR': 'fr',
                            'fr-CA': 'fr',
                            'de-DE': 'de',
                            'it-IT': 'it',
                            'pt-BR': 'pt',
                            'pt-PT': 'pt',
                            'ru-RU': 'ru',
                            'ja-JP': 'ja',
                            'ko-KR': 'ko',
                            'zh-CN': 'zh',  // Simplified Chinese
                            'zh-TW': 'zh',  // Traditional Chinese (LibreTranslate uses 'zh' for both)
                            'ar-SA': 'ar',
                            'hi-IN': 'hi',
                            'nl-NL': 'nl',
                            'sv-SE': 'sv',
                            'no-NO': 'no',
                            'da-DK': 'da',
                            'fi-FI': 'fi',
                            'pl-PL': 'pl',
                            'tr-TR': 'tr'
                        };

                        // Initialize voice detection
                        document.addEventListener('DOMContentLoaded', function() {
                            translationStatusElement = document.getElementById('translation-status');
                            translationResultElement = document.getElementById('translation-result');
                            translationMessageElement = document.getElementById('translation-message');

                            const languageSelect = document.getElementById('tts-language');
                            if (languageSelect) {
                                languageSelect.addEventListener('change', function() {
                                    selectedLanguage = this.value;
                                    // Stop any ongoing speech when language changes
                                    if (isSpeaking) {
                                        speechSynthesis.cancel();
                                        isSpeaking = false;
                                    }
                                });
                            }

                            // Load voices when they become available
                            loadVoices();
                        });

                        function loadVoices() {
                            availableVoices = speechSynthesis.getVoices();

                            if (availableVoices.length === 0) {
                                // Voices not loaded yet, try again after a short delay
                                setTimeout(loadVoices, 100);
                                return;
                            }
                        }

                        // Translation function using multiple services for reliability
                        async function translateText(text, targetLang) {
                            try {
                                // If target language is English, return original text
                                if (targetLang === 'en') {
                                    console.log('Target language is English, no translation needed');
                                    return text;
                                }

                                console.log(`Translating "${text.substring(0, 50)}..." to ${targetLang}`);

                                // Method 1: Try LibreTranslate.de
                                try {
                                    const response = await fetch('https://libretranslate.de/translate', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify({
                                            q: text,
                                            source: 'auto',
                                            target: targetLang,
                                            format: 'text'
                                        })
                                    });

                                    if (response.ok) {
                                        const data = await response.json();
                                        console.log('LibreTranslate.de response:', data);
                                        if (data.translatedText && data.translatedText.trim() !== '') {
                                            console.log(`✅ Translation successful: ${data.translatedText.substring(0, 50)}...`);
                                            return data.translatedText;
                                        }
                                    } else {
                                        console.log('LibreTranslate.de failed:', response.status, response.statusText);
                                    }
                                } catch (error) {
                                    console.log('LibreTranslate.de error:', error);
                                }

                                // Method 2: Try alternative LibreTranslate endpoint
                                try {
                                    const altResponse = await fetch('https://translate.argosopentech.com/translate', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify({
                                            q: text,
                                            source: 'auto',
                                            target: targetLang,
                                            format: 'text'
                                        })
                                    });

                                    if (altResponse.ok) {
                                        const altData = await altResponse.json();
                                        console.log('Alternative LibreTranslate response:', altData);
                                        if (altData.translatedText && altData.translatedText.trim() !== '') {
                                            console.log(`✅ Alternative translation successful: ${altData.translatedText.substring(0, 50)}...`);
                                            return altData.translatedText;
                                        }
                                    }
                                } catch (altError) {
                                    console.log('Alternative LibreTranslate error:', altError);
                                }

                                // Method 3: Try MyMemory API (free alternative)
                                try {
                                    const myMemoryResponse = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=en|${targetLang}`);
                                    if (myMemoryResponse.ok) {
                                        const myMemoryData = await myMemoryResponse.json();
                                        console.log('MyMemory response:', myMemoryData);
                                        if (myMemoryData.responseData && myMemoryData.responseData.translatedText) {
                                            console.log(`✅ MyMemory translation successful: ${myMemoryData.responseData.translatedText.substring(0, 50)}...`);
                                            return myMemoryData.responseData.translatedText;
                                        }
                                    }
                                } catch (myMemoryError) {
                                    console.log('MyMemory error:', myMemoryError);
                                }

                                // Method 4: Try Google Translate via proxy
                                try {
                                    const proxyUrl = `https://cors-anywhere.herokuapp.com/https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`;
                                    const googleResponse = await fetch(proxyUrl);
                                    if (googleResponse.ok) {
                                        const googleData = await googleResponse.json();
                                        console.log('Google Translate response:', googleData);
                                        if (googleData && googleData[0] && googleData[0][0] && googleData[0][0][0]) {
                                            console.log(`✅ Google Translate successful: ${googleData[0][0][0].substring(0, 50)}...`);
                                            return googleData[0][0][0];
                                        }
                                    }
                                } catch (googleError) {
                                    console.log('Google Translate error:', googleError);
                                }

                                // If all methods fail, return original text
                                console.log('❌ All translation services failed, returning original text');
                                return text;

                            } catch (error) {
                                console.error('Translation error:', error);
                                return text; // Fallback to original text
                            }
                        }

                        function findMatchingVoices(language) {
                            if (availableVoices.length === 0) return [];

                            const languageCode = language.split('-')[0];
                            const countryCode = language.split('-')[1];

                            // First try exact match
                            let matchingVoices = availableVoices.filter(voice =>
                                voice.lang === language
                            );

                            // If no exact match, try language code match
                            if (matchingVoices.length === 0) {
                                matchingVoices = availableVoices.filter(voice =>
                                    voice.lang.startsWith(languageCode + '-')
                                );
                            }

                            // If still no match, try broader language code match
                            if (matchingVoices.length === 0) {
                                matchingVoices = availableVoices.filter(voice =>
                                    voice.lang.split('-')[0] === languageCode
                                );
                            }

                            // Sort by quality (prefer default voices and local voices)
                            return matchingVoices.sort((a, b) => {
                                if (a.default && !b.default) return -1;
                                if (!a.default && b.default) return 1;
                                if (a.localService && !b.localService) return -1;
                                if (!a.localService && b.localService) return 1;
                                return 0;
                            });
                        }

                        async function speakText(text, buttonElement) {
                            // If currently speaking, stop it
                            if (isSpeaking) {
                                speechSynthesis.cancel();
                                isSpeaking = false;
                                isTranslating = false;

                                // Update button icon back to play
                                if (buttonElement) {
                                    const icon = buttonElement.querySelector('i');
                                    if (icon) {
                                        icon.className = 'fa fa-volume-up';
                                    }
                                }

                                // Hide translation status
                                if (translationStatusElement) {
                                    translationStatusElement.classList.add('hidden');
                                }
                                return;
                            }

                            // If translating, wait for it to complete
                            if (isTranslating) {
                                return;
                            }

                            try {
                                isTranslating = true;

                                // Update button icon to stop
                                if (buttonElement) {
                                    const icon = buttonElement.querySelector('i');
                                    if (icon) {
                                        icon.className = 'fa fa-stop';
                                    }
                                }

                                // Show translation status
                                if (translationStatusElement) {
                                    translationStatusElement.classList.remove('hidden');
                                }

                                // Get target language code for translation
                                const targetLang = languageMap[selectedLanguage] || 'en';

                                // Translate the text
                                const translatedText = await translateText(text, targetLang);

                                isTranslating = false;

                                // Hide translation status and show result
                                if (translationStatusElement) {
                                    translationStatusElement.classList.add('hidden');
                                }

                                // Show translation result
                                if (translationResultElement && translationMessageElement) {
                                    if (translatedText !== text) {
                                        translationMessageElement.textContent = `✅ Translated to ${targetLang}`;
                                        translationResultElement.className = 'text-xs text-green-600';
                                    } else {
                                        translationMessageElement.textContent = `⚠️ Using original text (translation failed)`;
                                        translationResultElement.className = 'text-xs text-yellow-600';
                                    }
                                    translationResultElement.classList.remove('hidden');

                                    // Hide result after 3 seconds
                                    setTimeout(() => {
                                        translationResultElement.classList.add('hidden');
                                    }, 3000);
                                }

                                const utterance = new SpeechSynthesisUtterance(translatedText);
                                utterance.lang = selectedLanguage;

                                // Get the best matching voice
                                const matchingVoices = findMatchingVoices(selectedLanguage);
                                if (matchingVoices.length > 0) {
                                    utterance.voice = matchingVoices[0];
                                }

                                // Set speech parameters for better quality
                                utterance.rate = 0.9; // Slightly slower for better comprehension
                                utterance.pitch = 1.0; // Normal pitch
                                utterance.volume = 1.0; // Full volume

                                utterance.onstart = () => {
                                    isSpeaking = true;
                                };

                                utterance.onend = () => {
                                    isSpeaking = false;
                                    // Update button icon back to play
                                    if (buttonElement) {
                                        const icon = buttonElement.querySelector('i');
                                        if (icon) {
                                            icon.className = 'fa fa-volume-up';
                                        }
                                    }
                                };

                                utterance.onerror = (event) => {
                                    isSpeaking = false;
                                    // Update button icon back to play
                                    if (buttonElement) {
                                        const icon = buttonElement.querySelector('i');
                                        if (icon) {
                                            icon.className = 'fa fa-volume-up';
                                        }
                                    }
                                    console.error('Speech synthesis error:', event.error);
                                };

                                speechSynthesis.speak(utterance);
                            } catch (error) {
                                isTranslating = false;
                                isSpeaking = false;

                                // Update button icon back to play
                                if (buttonElement) {
                                    const icon = buttonElement.querySelector('i');
                                    if (icon) {
                                        icon.className = 'fa fa-volume-up';
                                    }
                                }

                                // Hide translation status
                                if (translationStatusElement) {
                                    translationStatusElement.classList.add('hidden');
                                }

                                console.error('Translation or speech error:', error);

                                // Fallback: speak original text
                                const utterance = new SpeechSynthesisUtterance(text);
                                utterance.lang = selectedLanguage;
                                speechSynthesis.speak(utterance);
                            }
                        }

                        // Load voices when they become available
                        if (speechSynthesis.onvoiceschanged !== undefined) {
                            speechSynthesis.onvoiceschanged = loadVoices;
                        }

                        // Debug function to test all language mappings
                        function testAllLanguageMappings() {
                            console.log('Testing all language mappings:');
                            Object.keys(languageMap).forEach(ttsLang => {
                                const translationLang = languageMap[ttsLang];
                                console.log(`${ttsLang} -> ${translationLang}`);
                            });
                        }

                        // Call test function on page load for debugging
                        document.addEventListener('DOMContentLoaded', function() {
                            setTimeout(testAllLanguageMappings, 1000);
                        });
                    </script>

    <script>
        function toggleFaq(element) {
            const content = element.nextElementSibling;
            const arrow = element.querySelector('svg');

            content.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }

        const warning = "{{ session('warning') }}";
        if (warning) {
            Swal.fire({
                title: "Failed",
                text: warning,
                icon: "error"
            });
        }
        var message = "{{ session('success') }}";
        if (message) {
            Swal.fire({
                title: "Success",
                text: message,
                icon: "success"
            });
        }
    </script>

</x-app-layout>
