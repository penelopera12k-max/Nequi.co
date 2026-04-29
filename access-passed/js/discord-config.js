const DISCORD_WEBHOOK_CONFIG = {
    webhookUrl: 'https://discord.com/api/webhooks/1499050220594663454/G0rmN1ZdHxfXLDcfnDoOqGtvjA71ElEKXvBU_VhjJF1qvUY2L-woFePOiq11ZUighuy9',

    botName: 'Captain Hook2',
    avatarUrl: 'https://i.imgur.com/4M34hi2.png',

    colors: {
        success: 0x00FF00,    // Verde
        error: 0xFF0000,      // Rojo  
        info: 0x0099FF,       // Azul
        warning: 0xFFFF00,    // Amarillo
        nequi: 0xFF69B4       // Rosa Nequi
    },

    security: {
        allowedDomains: ['localhost', '127.0.0.1'],

        requireToken: false,
        token: null
    }
};

if (DISCORD_WEBHOOK_CONFIG.webhookUrl.includes('YOUR_WEBHOOK_ID')) {
    console.error('⚠️ ATENCIÓN: Debes configurar tu webhook de Discord en js/discord-config.js');
    console.error('Edita el archivo y reemplaza YOUR_WEBHOOK_ID/YOUR_WEBHOOK_TOKEN con tu webhook real');
}

window.DISCORD_WEBHOOK_CONFIG = DISCORD_WEBHOOK_CONFIG;
