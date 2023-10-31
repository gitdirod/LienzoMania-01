import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            output:'public',
            refresh: true,
        }),
    ],
});
// import { defineConfig } from 'vite';
// import vue from '@vitejs/plugin-vue';
// import purgecss from '@fullhuman/postcss-purgecss';

// export default defineConfig({
//   plugins: [
//     vue(),
//     // Agrega el complemento de PurgeCSS para eliminar clases no utilizadas
//     purgecss({
//       content: ['./index.html', './src/**/*.vue'],
//       safelist: [],
//       // Configura aquí las opciones de PurgeCSS según sea necesario
//     }),
//   ],

//   // ... Otras configuraciones de Vite ...
// });

