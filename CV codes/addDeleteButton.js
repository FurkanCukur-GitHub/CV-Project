function addElement(containerId, elementName, additionalFields = []) {
    var container = document.getElementById(containerId);
    var div = document.createElement("div");

    var element = document.createElement("span");
    element.textContent = elementName + " : ";
    div.appendChild(element)

    var inputName = document.createElement("input");
    inputName.type = "text";
    inputName.name = elementName + "Names[]";
    inputName.required = true;
    div.appendChild(inputName);

    div.appendChild(document.createTextNode(" "));

    additionalFields.forEach(function (field) {
        var fieldName = field.nameSuffix.replace('[]', '');

        if (fieldName.endsWith('s')) {
            fieldName = fieldName.slice(0, -1);
        }

        var element = document.createElement("span");
        element.textContent = fieldName + " : ";
        div.appendChild(element);

        var input = document.createElement("input");
        input.type = field.type;
        input.name = elementName + field.nameSuffix;

        input.required = true;

        div.appendChild(input);

        div.appendChild(document.createTextNode(" "));
    });

    container.appendChild(div);
}

function deleteElement(containerId) {
    var container = document.getElementById(containerId);
    if (container.childElementCount > 0) {
        container.removeChild(container.lastElementChild);
    }
}
