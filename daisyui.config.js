module.exports = {
  content: ["./*.html", "./js/*.js"],
  plugins: [require("daisyui")],
  daisyui: {
    themes: [{
      "retroznak": {
        "primary": "#f97316",        // Оранжевый акцент
        "primary-focus": "#ea580c",  // Темнее оранжевый
        "primary-content": "#ffffff", // Белый текст на оранжевом

        "secondary": "#374151",      // Темно-серый
        "secondary-focus": "#1f2937", // Еще темнее
        "secondary-content": "#f9fafb", // Светлый текст

        "accent": "#f97316",         // Оранжевый акцент
        "accent-focus": "#ea580c",   // Темнее
        "accent-content": "#ffffff", // Белый текст

        "neutral": "#1f2937",        // Темный фон
        "neutral-focus": "#111827",  // Темнее
        "neutral-content": "#f9fafb", // Светлый текст

        "base-100": "#111827",       // Основной темный
        "base-200": "#1f2937",       // Темнее
        "base-300": "#374151",       // Еще темнее
        "base-content": "#f9fafb",   // Светлый текст

        "info": "#3b82f6",           // Синий для информации
        "info-content": "#ffffff",

        "success": "#10b981",        // Зеленый для успеха
        "success-content": "#ffffff",

        "warning": "#f59e0b",        // Желтый для предупреждений
        "warning-content": "#ffffff",

        "error": "#ef4444",          // Красный для ошибок
        "error-content": "#ffffff"
      }
    }],
    base: false,
    utils: true,
  },
}