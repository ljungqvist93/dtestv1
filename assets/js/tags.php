<script>
let tagInput = document.getElementById('newtaginput');
let tagButton = document.getElementById('newtagbutton');
let allTags = document.getElementById('alltags');
let tags = document.getElementsByClassName('tagentries');
let selectedTags = document.getElementById('selectedtags');
let allTagsList = [];
let chosenTags = [];

function updateList() {
    let chosenIds = chosenTags.map(e => e.id);
    for(let i = 0; i < tags.length; i++) {
        if(chosenIds.indexOf(tags[i].dataset.id) === -1) {
            tags[i].style.display = 'block';
            continue;
        }
        tags[i].style.display = 'none';
    }
}

function createElement(id, label) {
    let wrapperElement = document.createElement('div');
    wrapperElement.id = 'tag_'+id;
    wrapperElement.className = 'tag';
    let inputElement = document.createElement('input');
    inputElement.type = 'hidden';
    inputElement.name = 'tags[]';
    inputElement.value = id;
    let labelElement = document.createElement('span');
    labelElement.innerHTML = label;
    labelElement.style.marginLeft = '4px';
    let deleteElement = document.createElement('i');
    deleteElement.className = 'fa fa-trash text-red';
    deleteElement.dataset.id = id;
    deleteElement.style.cursor = 'pointer';
    deleteElement.onclick = e => {
        e.target.parentNode.parentNode.removeChild(e.target.parentNode);
        let newArray = [];
        for(let j = 0; j < chosenTags.length; j++) {
            if(chosenTags[j].id === e.target.dataset.id) {
                continue;
            }
            newArray.push(chosenTags[j]);
        }
        chosenTags = newArray;
        updateList();
    };
    wrapperElement.appendChild(inputElement);
    wrapperElement.appendChild(deleteElement);
    wrapperElement.appendChild(labelElement);
    selectedTags.appendChild(wrapperElement);
    chosenTags.push({
        id,
        label,
    });
    updateList();
    tagInput.value = '';
}

{
    let tagsOnLoad = [
        <?php foreach($postTags as $tag): ?>
            {
                id: '<?= $tag['id']; ?>',
                label: '<?= $tag['label']; ?>',
            },
        <?php endforeach; ?>
    ];
    for(let i = 0; i < tagsOnLoad.length; i++) {
        createElement(tagsOnLoad[i].id, tagsOnLoad[i].label);
    }
}

for(let i = 0; i < tags.length; i++) {
    tags[i].onmousedown = e => {
        let id = e.target.dataset.id;
        if(chosenTags.map(e => e.id).indexOf(id) !== -1) {
            return;
        }
        let label = e.target.dataset.label;
        createElement(id, label);
    };
}
tagInput.onfocus = e => {
    allTags.style.display = 'block';
};
tagInput.onblur = e => {
    allTags.style.display = 'none';
};
tagInput.oninput = e => {
    let input = e.target.value.toLowerCase();
    for(let i = 0; i < tags.length; i++) {
        let id = tags[i].dataset.id;
        let chosenIds = chosenTags.map(e => e.id);
        if(chosenIds.indexOf(id) !== -1) {
            continue;
        }
        let label = tags[i].dataset.label.toLowerCase();
        if(label.includes(input)) {
            tags[i].style.display = 'block';
            continue;
        }
        tags[i].style.display = 'none';
    }
};
tagButton.onClick = e => {
    e.preventDefault();
    let label = tagInput.value;
    if(label === '') {
        return;
    }
    createElement(label, label);
};
</script>
