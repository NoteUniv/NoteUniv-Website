tippy("a.tippy-note", {
    placement: "right",
    arrow: false,
    trigger: "click",
    maxWidth: 10000,
    theme: "note",
    allowHTML: true,
    interactive: true,
    flipOnUpdate: true,
    content(reference) {
        const id = reference.getAttribute("data-template");
        const template = document.getElementById(id);
        return template.innerHTML;
    }
});

tippy("span.tippy-note", {
    placement: "top",
    arrow: true,
    minWidth: 1000,
    theme: "noteSpan",
    allowHTML: true,
    interactive: true,
    flipOnUpdate: true
});