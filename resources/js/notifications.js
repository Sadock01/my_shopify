/**
 * Service de notifications élégantes
 */
class NotificationService {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Créer le conteneur des notifications s'il n'existe pas
        if (!document.getElementById('notifications-container')) {
            this.container = document.createElement('div');
            this.container.id = 'notifications-container';
            this.container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('notifications-container');
        }
    }

    /**
     * Afficher une notification
     * @param {string} message - Le message à afficher
     * @param {string} type - Le type de notification (success, error, warning, info)
     * @param {number} duration - Durée d'affichage en millisecondes (défaut: 8000)
     */
    show(message, type = 'success', duration = 8000) {
        const notification = this.createNotification(message, type, duration);
        this.container.appendChild(notification);

        // Animation d'entrée
        setTimeout(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
            notification.classList.add('translate-x-0', 'opacity-100');
        }, 100);

        // Auto-suppression
        setTimeout(() => {
            this.hide(notification);
        }, duration);
    }

    /**
     * Créer l'élément de notification
     */
    createNotification(message, type, duration) {
        const notification = document.createElement('div');
        notification.className = `
            bg-white rounded-lg shadow-2xl border-l-4 min-w-80 max-w-96 overflow-hidden
            transform translate-x-full opacity-0 transition-all duration-500 ease-in-out
            ${this.getTypeClasses(type)}
        `;

        notification.innerHTML = `
            <div class="flex items-start p-4">
                <!-- Icône -->
                <div class="flex-shrink-0 mr-3">
                    ${this.getIcon(type)}
                </div>
                
                <!-- Contenu -->
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-gray-900 mb-1">
                        ${this.getTitle(type)}
                    </h4>
                    <p class="text-sm text-gray-600 leading-relaxed">${message}</p>
                </div>
                
                <!-- Bouton de fermeture -->
                <div class="flex-shrink-0 ml-3">
                    <button class="text-gray-400 hover:text-gray-600 transition-colors duration-200 close-btn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Barre de progression -->
            <div class="h-1 bg-gray-200">
                <div class="h-full transition-all duration-${duration} ease-linear progress-bar ${this.getProgressBarClass(type)}" 
                     style="width: 0%"></div>
            </div>
        `;

        // Gestionnaire de fermeture
        const closeBtn = notification.querySelector('.close-btn');
        closeBtn.addEventListener('click', () => this.hide(notification));

        // Démarrer la barre de progression
        setTimeout(() => {
            const progressBar = notification.querySelector('.progress-bar');
            if (progressBar) {
                progressBar.style.width = '100%';
            }
        }, 100);

        return notification;
    }

    /**
     * Masquer une notification
     */
    hide(notification) {
        notification.classList.remove('translate-x-0', 'opacity-100');
        notification.classList.add('translate-x-full', 'opacity-0');

        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 500);
    }

    /**
     * Obtenir les classes CSS selon le type
     */
    getTypeClasses(type) {
        const classes = {
            success: 'border-green-500',
            error: 'border-red-500',
            warning: 'border-yellow-500',
            info: 'border-blue-500'
        };
        return classes[type] || classes.success;
    }

    /**
     * Obtenir l'icône selon le type
     */
    getIcon(type) {
        const icons = {
            success: `
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            `,
            error: `
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            `,
            warning: `
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            `,
            info: `
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            `
        };
        return icons[type] || icons.success;
    }

    /**
     * Obtenir le titre selon le type
     */
    getTitle(type) {
        const titles = {
            success: 'Succès',
            error: 'Erreur',
            warning: 'Attention',
            info: 'Information'
        };
        return titles[type] || titles.success;
    }

    /**
     * Obtenir la classe de la barre de progression
     */
    getProgressBarClass(type) {
        const classes = {
            success: 'bg-gradient-to-r from-green-500 to-green-400',
            error: 'bg-gradient-to-r from-red-500 to-red-400',
            warning: 'bg-gradient-to-r from-yellow-500 to-yellow-400',
            info: 'bg-gradient-to-r from-blue-500 to-blue-400'
        };
        return classes[type] || classes.success;
    }

    // Méthodes de commodité
    success(message, duration = 8000) {
        this.show(message, 'success', duration);
    }

    error(message, duration = 10000) {
        this.show(message, 'error', duration);
    }

    warning(message, duration = 9000) {
        this.show(message, 'warning', duration);
    }

    info(message, duration = 8000) {
        this.show(message, 'info', duration);
    }
}

// Initialiser le service global
window.NotificationService = new NotificationService();

// Fonctions globales pour faciliter l'utilisation
window.showNotification = (message, type = 'success', duration = 8000) => {
    window.NotificationService.show(message, type, duration);
};

window.showSuccess = (message, duration = 8000) => {
    window.NotificationService.success(message, duration);
};

window.showError = (message, duration = 10000) => {
    window.NotificationService.error(message, duration);
};

window.showWarning = (message, duration = 9000) => {
    window.NotificationService.warning(message, duration);
};

window.showInfo = (message, duration = 8000) => {
    window.NotificationService.info(message, duration);
};
