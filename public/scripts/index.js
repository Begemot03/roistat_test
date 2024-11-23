const FEEDBACK_API = "/api/feedback";

window.onload = app;

async function app() {
	const response = await fetchJson(FEEDBACK_API, "POST");
    const json = await response.json();

    console.log(json);
}

async function fetchJson(path, method, body = {}) {
    return fetch(path, {
        method,
        body: JSON.stringify(body),
        headers: {
            "Content-Type": "application/json",
        },
    });
}
