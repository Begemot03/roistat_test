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
const pageLoadTime = Date.now();

window.onload = app;

function app() {
	addListeners();
}

function addListeners() {
	const form = document.querySelector("form");

	form.addEventListener("submit", submitFeedback);
}

async function submitFeedback(e) {
	e.preventDefault();

	const form = e.target;
	const formData = new FormData(form);

	if (!validateForm(form, validationSchema)) return;

	const body = Object.fromEntries(formData);
	body.was30sec = was30Sec();


	try {
		const response = await fetchJson(FEEDBACK_API, "POST", body);
		const msg = await response.json();

		if (!response.ok) {
			serverErrorHandler(msg.error);
			return;
		}

		successHandler(JSON.parse(msg)._embedded.leads[0].id);
	} catch (e) {
		serverErrorHandler(e);
	}
}

function serverErrorHandler(msg) {
	alert(`Произошла ошибка ${msg}`);
}

function successHandler(msg) {
	alert(`Успех: id ${msg}`);
}

function validateForm(form, schema) {
	let isFormValid = true;

	schema.forEach((instruction) => {
		const input = form.querySelector(`input#${instruction.name}`);
		const help = input.parentNode.parentNode.querySelector("p");
		const [isValid, message] = instruction.func(input.value.trim());

		isFormValid &&= isValid;
		if (help) help.textContent = message;
	});

	return isFormValid;
}

function validName(name) {
	if (name != "") return [true, ""];

	return [false, "Имя обязательное поле"];
}

function validEmail(email) {
	const reg = /^\S+@\S+\.\S+$/;
	if (reg.test(email)) return [true, ""];

	return [false, "Введите корректный email"];
}

function validPhone(phone) {
	const reg =
		/^(\+7|8)(\s|-)?(\()?[0-9]{3}(\))?(\s|-)?([0-9]{3})(\s|-)?([0-9]{2})(\s|-)?([0-9]{2})$/;
	if (reg.test(phone)) return [true, ""];

	return [false, "Введите корректный номер телефона"];
}

function validPrice(price) {
	if (price != "" && !isNaN(+price)) return [true, ""];

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

function was30Sec() {
	const timeSpent = (Date.now() - pageLoadTime) / 1000;
	return timeSpent >= 30;
}
