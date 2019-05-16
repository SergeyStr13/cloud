
function togglePassword(el) {
	var inputEl = el.previousSibling;
	if (inputEl.type === 'password') {
		inputEl.type = 'text';
		el.setAttribute('title', 'Скрыть пароль');
	} else {
		inputEl.type = 'password';
		el.setAttribute('title', 'Показать пароль');
	}
}