let brand,model,year = null;
const brandSelect = document.getElementById("brand");
const modelSelect = document.getElementById("model");
const yearSelect = document.getElementById("year");

const modelDis = document.createElement('option');
modelDis.value ='dis';
modelDis.innerHTML ='Select a model';
modelDis.disabled = true;
const yearDis = document.createElement('option');
yearDis.value ='dis';
yearDis.innerHTML ='Select a year';
yearDis.disabled = true;

document.addEventListener("DOMContentLoaded", () => {
    brand,model,year = null;
    brandSelect.value = "dis";
    modelSelect.value = "dis";
    yearSelect.value = "dis";
});

const handleSelectChange = (it) => {
    const name = it.name;
    switch(name) {
        case 'brand': {
            brand = it.value;
            for (let i = modelSelect.options.length;i>=0;i--) {
                modelSelect.options[i] = null;
            }
            modelSelect.appendChild(modelDis);
            const data = {
                brand: brand
            }
            fetch('./utils/search/models.php', {
                method: 'POST',
                body: JSON.stringify(data),
            }).then(async (data)=>{
                const result = await data.json();
                result.forEach(async (el) => {
                    const newOpt = document.createElement('option');
                    newOpt.value = el['0'];
                    newOpt.innerHTML = el['0'];
                    modelSelect.appendChild(newOpt);
                })
            })
            model = year = null;
        }
        break;
        case 'model': {
            model = it.value;
            for (let i = yearSelect.options.length;i>=0;i--) {
                yearSelect.options[i] = null;
            }
            yearSelect.appendChild(yearDis);
            const data = {
                model: model
            }
            fetch('./utils/search/years.php', {
                method: 'POST',
                body: JSON.stringify(data),
            }).then(async (data)=>{
                const result = await data.json();
                result.forEach(async (el) => {
                    const newOpt = document.createElement('option');
                    newOpt.value = el['0'];
                    newOpt.innerHTML = el['0'];
                    yearSelect.appendChild(newOpt);
                })
            })
            year = null;
        }
        break;
        case 'year': {
            year = it.value;
        }
        break;
    }

    if (brand) {
        brandSelect.value = brand;
    } else {
        brandSelect.value = "dis";
    }
    if (model) {
        modelSelect.value = model;
    } else {
        modelSelect.value = "dis";
    }
    if (year) {
        yearSelect.value = year;
    } else {
        yearSelect.value = "dis";
    }
}

const onSubmit = () => {
    requestModel()
    return false;
}

const requestModel = async () => {
    const form = document.forms.search
    formdata = new FormData(form)

    fetch('./utils/search/search.php', {
        method: 'POST',
        body: formdata,
    }).then(async (data) =>{
        const result = await data.json();
        document.location.href = './model.php?id=' + result.id
    })
}