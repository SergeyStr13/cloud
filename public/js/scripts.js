// polyfills
if (!Array.prototype.forEach) {
	Array.prototype.forEach = function(callback) {
		var arr = this;
		var length = arr.length;
		var i = -1;

		if (typeof callback !== 'function') {
			throw new TypeError('Array.prototype.forEach: callback must be a function');
		}

		while (++i < length) {
			if (i in arr) {
				callback(arr[i], i, arr);
			}
		}
	};
}

if (!Array.prototype.filter) {
	Array.prototype.filter = function(callback) {
		var arr = this;
		var length = arr.length;
		var i = -1;

		if (typeof callback !== 'function') {
			throw new TypeError('Array.prototype.filter: callback must be a function');
		}

		var res = [];
		while (++i < length) {
			if (i in arr) {
				if (callback(arr[i], i, arr)) {
					res.push(arr[i]);
				}
			}
		}
		return res;
	};
}

if (!Array.prototype.includes) {
	Array.prototype.includes = function(value) {
		return this.indexOf(value) !== -1;
	};
}

if (!Element.prototype.matches) {
	Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}

if (!Element.prototype.closest) {
	Element.prototype.closest = function(selector) {
		var el = this;
		do {
			if (el.matches(selector)) {
				return el;
			}
			el = el.parentElement || el.parentNode;
		} while (el !== null && el.nodeType === 1);
		return null;
	};
}

Element.prototype.childrenBySelector = function(selector) {
	var res = [];
	for (var i = 0; i < this.childNodes.length; i++) {
		var el = this.childNodes[i];
		if (el.nodeType !== Node.TEXT_NODE && el.matches(selector)) {
			res.push(el);
		}
	}
	return res;
};

Element.prototype.getIndex = function(element) {
	var index = 0;
	for (var i = 0; i < this.childNodes.length; i++) {
		var el = this.childNodes[i];
		if (el.nodeType !== Node.TEXT_NODE) {
			if (el === element) {
				return index;
			}
			index++;
		}
	}
	return -1;
};

// shorthands
Element.prototype.bySelector = function(selectors) {
	return this.querySelector(selectors);
};
Element.prototype.byClass = function(className) {
	return this.getElementsByClassName(className)[0];
};
Element.prototype.allByClass = function(className) {
	return this.getElementsByClassName(className);
};
Element.prototype.allByName = function(elementName) {
	return this.getElementsByName(elementName);
};
Element.prototype.allBySelector = function(selectors) {
	return this.querySelectorAll(selectors);
};
Element.prototype.wrap = function(wrapEl) {
	this.parentNode.insertBefore(wrapEl, this);
	wrapEl.appendChild(this);
	return wrapEl;
};
Element.prototype.prepend = function(el) {
	this.parent.insertBefore(el, this);
};
Element.prototype.append = function(el) {
	this.parent.insertBefore(el, this.nextSibling);
};

if (Element.prototype.addEventListener) {
	Element.prototype.addEvent = Element.prototype.addEventListener;
} else {
	Element.prototype.addEvent = function(event, func) {
		this.attachEvent("on" + event, func);
	};
}
Document.prototype.addEvent = Element.prototype.addEvent;

HTMLElement.prototype.addClass = function(className) {
	this.className = (this.className ? this.className + " " : "") + className;
};
HTMLElement.prototype.removeClass = function(className) {
	this.className = this.className.replace(new RegExp(className, "g"), "");
};
HTMLElement.prototype.hasClass = function(className) {
	return new RegExp(className, "g").test(this.className);
};
HTMLElement.prototype.toggleClass = function(className) {
	if (this.hasClass(className)) {
		this.removeClass(className);
	} else {
		this.addClass(className);
	}
};

window.byId = function(id) {
	return document.getElementById(id);
};
window.bySelector = Element.prototype.bySelector.bind(document);
window.byClass = Element.prototype.byClass.bind(document);
window.allByClass = Element.prototype.allByClass.bind(document);
window.allByName = Element.prototype.allByName.bind(document);
window.allBySelector = Element.prototype.allBySelector.bind(document);

function filterKey(e, allowedChars) {
	var key = e.which || e.keyCode;
	if (e.ctrlKey || e.altKey || key === 0) {
		return;
	}
	var pattern = new RegExp('^['+allowedChars+']+$');
	if (!String.fromCharCode(key).match(pattern)) {
		e.preventDefault();
	}
}

function setVisible(element, isVisible) {
	const DISPLAY_ATTR = 'data-display-old';
	var style = element.style;
	if (isVisible) {
		if (style.display === 'none') {
			style.display = element.getAttribute(DISPLAY_ATTR) || '';
		}
	} else {
		if (style.display !== 'none') {
			if (style.display && !element.hasAttribute(DISPLAY_ATTR)) {
				element.setAttribute(DISPLAY_ATTR, style.display);
			}
			style.display = 'none';
		}
	}
}

function buttonSetEnabled(button, enabled) {
	if (enabled === undefined) {
		enabled = true;
	}
	if (enabled) {
		button.removeClass('disabled');
		button.removeAttribute('tabindex');
	} else {
		button.addClass('disabled');
		button.setAttribute('tabindex', -1);
	}
}

function checkAll(checkbox) {
	var column = checkbox.closest('th');
	var table = column.closest('table');
	var columnIndex = column.parentNode.getIndex(column);
	for (var i = 1; i < table.rows.length; i++ ) {
		 var cell = table.rows[i].cells[columnIndex];
		 var cellCheckbox = cell.bySelector('input[type="checkbox"]');
		 if (cellCheckbox) {
			cellCheckbox.checked = checkbox.checked;
		 }
	}
}



window.Utils = {

	toArray: function(value) {
		return Array.apply(null, value);
	}

};

// click outside
var clickOutsideElements = Utils.toArray(allBySelector('[data-click-outside]')).map( function(element) {
	var matches = element.getAttribute('data-click-outside').match(/(.+):(\w+)\.(.+)/);
	var targetSelector = '.' + matches[1];
	var action = matches[2];
	var actionArgument = matches[3];
	if (action === 'removeClass') {
		return {element: element, func: function() {
			var targetElement = element.bySelector(targetSelector);
			if (targetElement) {
				targetElement[action](actionArgument);
			}
		}};
	}
});

document.addEvent('click', function(event) {
	clickOutsideElements.forEach( function(item) {
		if (!item.element.contains(event.target)) {
			item.func();
		}
	});
});


// Vue
window.Vue = App.Vue;

// Vue components
/* function Component(el, component) {
	if (typeof el === 'string') {
		el = bySelector(el);
	}
	return new Vue({
		el: el.wrap(document.createElement('div'))
	});
} */