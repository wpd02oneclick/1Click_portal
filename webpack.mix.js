import { inProduction, js } from "laravel-mix";

if (!inProduction()) {
    js("resources/js/app.js", "public/js")
        .sass("resources/sass/app.scss", "public/css")
        .babelConfig({
            presets: ["@babel/preset-env"],
        })
        .options({
            processCssUrls: false,
        })
        .sourceMaps();

    js("resources/js/notification.js", "public/js");
} else {
    // Production mode configuration
    js("resources/js/app.js", "public/js")
        .sass("resources/sass/app.scss", "public/css")
        .babelConfig({
            presets: ["@babel/preset-env"],
        })
        .options({
            processCssUrls: false,
        });

    js("resources/js/notification.js", "public/js")
        .styles(["public/assets/**/*.css"], "public/css/all.css")
        .styles(["public/assets/**/**.css"], "public/css/all.css")
        .scripts(["public/assets/**/*.js"], "public/js/all.js")
        .scripts(["public/assets/**/**.js"], "public/js/all.js")
        .version();
}
