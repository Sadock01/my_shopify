@extends('shop.layout')

@section('title', 'Preuve de paiement')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Preuve de paiement</h1>
            <p class="text-lg text-gray-600">Téléchargez votre preuve de paiement pour finaliser votre commande</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Télécharger votre preuve</h2>
                <p class="text-gray-600">Veuillez télécharger une photo ou un scan de votre reçu de virement bancaire</p>
            </div>

            <!-- Upload Form -->
            <form action="/upload-payment-proof/{{ $shop->slug }}" method="POST" enctype="multipart/form-data" id="payment-proof-form">
                @csrf
                
                <div class="space-y-6">
                    <!-- File Upload Area -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition-colors" id="upload-area">
                        <div id="upload-content">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-lg font-medium text-gray-900 mb-2">Cliquez pour sélectionner un fichier</p>
                            <p class="text-sm text-gray-500">ou glissez-déposez votre fichier ici</p>
                            <p class="text-xs text-gray-400 mt-2">Formats acceptés: JPG, PNG, PDF (max 5MB)</p>
                        </div>
                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*,.pdf" class="hidden" required>
                        <div id="file-preview" class="hidden">
                            <div class="flex items-center justify-center space-x-3">
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900" id="file-name"></p>
                                    <p class="text-sm text-gray-500" id="file-size"></p>
                                </div>
                                <button type="button" id="remove-file" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Instructions importantes</h3>
                        <ul class="space-y-2 text-blue-800">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Assurez-vous que votre reçu de virement est clairement lisible</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Le montant et les informations de virement doivent être visibles</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Votre commande sera traitée après vérification du paiement</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="button" onclick="history.back()" class="flex-1 bg-gray-100 text-gray-900 py-3 px-6 rounded-md font-semibold hover:bg-gray-200 transition-colors text-center">
                            Retour
                        </button>
                        <button type="submit" id="submit-btn" disabled class="flex-1 bg-primary text-white py-3 px-6 rounded-md font-semibold hover:opacity-90 transition-opacity text-center disabled:opacity-50 disabled:cursor-not-allowed">
                            Envoyer la preuve
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Navigation -->
            <div class="mt-6 text-center">
                <a href="{{ route('shop.home.slug', ['shop' => $shop->slug]) }}" class="text-gray-600 hover:underline">
                    ← Retour à l'accueil de la boutique
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('payment_proof');
    const uploadContent = document.getElementById('upload-content');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('remove-file');
    const submitBtn = document.getElementById('submit-btn');

    // Click to upload
    uploadArea.addEventListener('click', () => fileInput.click());

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('border-primary', 'bg-primary-50');
    });

    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-primary', 'bg-primary-50');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-primary', 'bg-primary-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
    });

    // Remove file
    removeFileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        resetUpload();
    });

    function handleFile(file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format de fichier non supporté. Veuillez sélectionner un fichier JPG, PNG ou PDF.');
            return;
        }

        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Le fichier est trop volumineux. Taille maximale: 5MB.');
            return;
        }

        // Update UI
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        uploadContent.classList.add('hidden');
        filePreview.classList.remove('hidden');
        submitBtn.disabled = false;
    }

    function resetUpload() {
        fileInput.value = '';
        uploadContent.classList.remove('hidden');
        filePreview.classList.add('hidden');
        submitBtn.disabled = true;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Form submission
    document.getElementById('payment-proof-form').addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Veuillez sélectionner un fichier de preuve de paiement.');
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Envoi en cours...';
    });
});
</script>
@endpush
