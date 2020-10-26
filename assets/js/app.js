import Error404 from "./views/404.js";
import Dashboard from "./views/Dashboard.js";
import Login from "./views/Login.js";
import Logout from "./views/Logout.js";

"use strict"

const pathToReg = path => new RegExp("^" + path.replace(/\//g, "\\/").replace(/:\w+/g, "(.+)") + "$");

const getParams = match => {
    const values = match.result.slice(1);
    const keys = Array.from(match.route.path.matchAll(/:(\w+)/g)).map(result => result[1]);
    return Object.fromEntries(keys.map((key, i) => {
        return [key, values[i]];
    }));
};

const navTo = url => {
    history.pushState(null, null, url);
};

const router = async () => {
    const routes = [
        { path: "/404", view: Error404 },
        { path: "/", view: Dashboard },
        { path: "/login", view: Login },
        { path: "/logout", view: Logout }
    ];

    // Test each route for potential match
    const optMatch = routes.map(route => {
        return {
            route: route,
            result: location.pathname.match(pathToReg(route.path))
        };
    });

    let match = optMatch.find(optMatch => optMatch.result !== null);

    if (!match) {
        match = {
            route: routes[0],
            result: [location.pathname]
        };
    }

    navTo(match.route.path);

    let app = document.querySelector("#app");
    const view = new match.route.view(getParams(match));
    app.innerHTML += await view.render();
    await view.after_render();
};

window.addEventListener("popstate", router);

document.addEventListener("DOMContentLoaded", router);


