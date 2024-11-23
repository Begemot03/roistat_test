const FEEDBACK_API = "/api/feedback";

const validationSchema = [
	{
		name: "name",
		func: validName,
	},
	{
		name: "email",
		func: validEmail,
	},
	{
		name: "phone",
		func: validPhone,
	},
	{
		name: "price",
		func: validPrice,
	},
];

window.onload = app;

function app() {
	addListeners();
}

function addListeners() {
	const form = document.querySelector("form");

	form.addEventListener("submit", submitFeedback);
}

function submitFeedback(e) {
	e.preventDefault();

	const form = e.target;
	const formData = new FormData(form);

	validateForm(form, validationSchema);

}


function validateForm(form, schema) {
	let isFormValid = true;

	schema.forEach((instruction) => {
		const input = form.querySelector(`input#${instruction.name}`);
		const help = input.parentNode.parentNode.querySelector("p");
		const [isValid, message] = instruction.func(input.value.trim());

		isFormValid &&= false;
        if(help) help.textContent = message;
	});

	return isFormValid;
}

function validName(name) {
    if(name != "") return [true, ""];

    return [false, "Имя обязательное поле"];
}

function validEmail(email) {
    const reg = /^\S+@\S+\.\S+$/
    if(reg.test(email)) return [true, ""];

    return [false, "Введите корректный email"]
}

function validPhone(phone) {
    const reg = /^(\+7|8)(\s|-)?(\()?[0-9]{3}(\))?(\s|-)?([0-9]{3})(\s|-)?([0-9]{2})(\s|-)?([0-9]{2})$/
    if(reg.test(phone)) return [true, ""];

    return [false, "Введите корректный номер телефона"]
}

function validPrice(price) {
    if(!isNaN(+price)) return [true, ""];

    return [false, "Введите число"];
}

function resetForm(form) {
	const inputs = form.querySelectorAll("input");

	inputs.forEach((input) => (input.value = ""));
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
