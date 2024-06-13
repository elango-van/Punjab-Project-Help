'use strict'

let curdatetime = new Date();
let zoom = 4.0;
const colorRange = ['#cf7834', '#7347cb', '#39ab3d', '#ce4ec6', '#75a03c', '#d23e73', '#42a181', '#d8412f', '#6378c2'];
const flclr = ['#fb6a4a', '#ef3b2c', '#cb181d', '#a50f15', '#67000d'];

let filteredDepartment = [], filteredImplAgency = [], filteredScheme = [], filteredMission = [];
let filteredData = [], filteredImplAgencyData = [], filteredSchemeData = [], filteredMissionData = [];

const objMission = document.getElementById('Mission');
const objNodalAgency = document.getElementById('NodalAgency');
const objStrategy = document.getElementById('Strategy');
const objImplementAgency = document.getElementById('ImplementAgency');
const objScheme = document.getElementById('Scheme');
const objClimateAction = document.getElementById('ClimateAction');
const objGeographical = document.getElementById('GeographicalCoverage');
const objDistrict = document.getElementById('District');
const objSelectedDistrict = document.getElementById('selectedDistrict');
const objFinancialYear = document.getElementById('FinancialYear');
const objAllocatedBudget = document.getElementById('AllocatedBudget');
const objActualExpenditure = document.getElementById('ActualExpenditure');
const objExpenditureTowardsClimate = document.getElementById('ExpenditureTowardsClimate');
const objWeightage = document.getElementById('Weightage');

const objSourceFunding = document.getElementById('SourceFunding');
const objtbSource = document.getElementById('tbsource');
const objshTable = document.getElementById('shtable');
const objNoshare = document.getElementById('noshare');

const objNature = document.getElementById('Nature');
const objLinkedSDG = document.getElementById('LinkedSDG');
const objLinkedNDC = document.getElementById('LinkedNDC');
const objKeyProgressIndicator = document.getElementById('KeyProgressIndicator');
const objNokpi = document.getElementById('nokpi');

const objAlert = document.getElementById("alertmessage");
const objMessage = document.getElementById("spnmessage");

function initializeForm() {

    // const objMission = document.getElementById('Mission');
    objMission.selectedIndex = 0;
    // const objNodalAgency = document.getElementById('NodalAgency');
    objNodalAgency.value = "";
    // const objStrategy = document.getElementById('Strategy');
    removeOptions(objStrategy);
    // const objImplementAgency = document.getElementById('ImplementAgency');
    removeOptions(objImplementAgency);
    // const objScheme = document.getElementById('Scheme');
    removeOptions(objScheme);
    // const objClimateAction = document.getElementById('ClimateAction');
    objClimateAction.value = "";
    // const objGeographical = document.getElementById('GeographicalCoverage');
    objGeographical.selectedIndex = 0;
    // const objDistrict = document.getElementById('District');
    removeMultipleSelectOptions(objDistrict)
    document.getElementById('divdistrict').style.display = 'none';
    // const objSelectedDistrict = document.getElementById('selectedDistrict');
    objSelectedDistrict.value = "";
    // const objFinancialYear = document.getElementById('FinancialYear');
    objFinancialYear.selectedIndex = 0;
    // const objAllocatedBudget = document.getElementById('AllocatedBudget');
    objAllocatedBudget.value = "";
    // const objActualExpenditure = document.getElementById('ActualExpenditure');
    objActualExpenditure.value = "";
    // const objExpenditureTowardsClimate = document.getElementById('ExpenditureTowardsClimate');
    objExpenditureTowardsClimate.value = "";
    // const objWeightage = document.getElementById('Weightage');
    objWeightage.value = "";
    // const objSourceFunding = document.getElementById('SourceFunding');
    objSourceFunding.selectedIndex = 0;
    // const objtbSource = document.getElementById('tbsource');
    objtbSource.style.display = 'none';
    // const objshTable = document.getElementById('shtable');
    // const objNoshare = document.getElementById('noshare');
    let norows = parseInt(objNoshare.value.trim());
    if (norows > 0) {
        for (let r = 0; r < norows; r++) {
            let availablerows = parseInt(deleteRow());
            console.log('Available rows : ' + availablerows);
        }
    }
    objNoshare.value = 0;
    // const objNature = document.getElementById('Nature');
    objNature.value = "";
    // const objLinkedSDG = document.getElementById('LinkedSDG');
    objLinkedSDG.value = ""
    // const objLinkedNDC = document.getElementById('LinkedNDC');
    objLinkedNDC.value = "";
    // const objKeyProgressIndicator = document.getElementById('KeyProgressIndicator');
    objKeyProgressIndicator.innerHTML = "";
    // const objNokpi = document.getElementById('nokpi');
    objNokpi.value = 0;
    document.getElementById('BriefDescription').value="";
    document.getElementById('PartnerAgencies').value="";
    document.getElementById('BestPractices').value="";
    document.getElementById('Remarks').value="";
    
}

// let testDiv = document.getElementById("map");
// let rect = testDiv.getBoundingClientRect();
// document.getElementById("map").style.height = (window.innerHeight - rect.top) + "px";
// //console.log([testDiv.offsetTop, window.innerHeight, (window.innerHeight - rect.top)]);

let map = new L.Map('map', {
    zoom: 4
});

// let satellite = L.tileLayer(viz.leaflet.tiles.OpenStreetMap.satellite, {
//     attribution: viz.leaflet.tiles.OpenStreetMap.attribution
// })

let osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
let osmAttrib = 'Map data &copy; OpenStreetMap contributors';
let osm = new L.TileLayer(osmUrl, {
    attribution: osmAttrib
});

let osmLayer = L.tileLayer(
    'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}@2x.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

function generateRandomColor() {
    let maxVal = 0xFFFFFF; // 16777215
    let randomNumber = Math.random() * maxVal;
    randomNumber = Math.floor(randomNumber);
    randomNumber = randomNumber.toString(16);
    let randColor = randomNumber.padStart(6, 0);
    // console.log(randColor.toUpperCase());
    return `#${randColor.toUpperCase()}`
}

let districtLayers = [];
const districtfile = "./jsons/Pun_Dist.json";
$.getJSON(districtfile, function (data) {

    // let objname = Object.keys(data.objects)[0];
    // let districtTopoJson = topojson.feature(data, data.objects[objname])

    districtLayers = L.geoJson(data, {
        onEachFeature: onEachDistrictLayer,
        // style: {
        //     fillColor:generateRandomColor(),
        //     weight: 1,
        //     color: '#000',
        //     fillOpacity: 0.5,
        //     // interactive: true,
        // }
    }).addTo(map);

    let nctbounds = districtLayers.getBounds();
    map.fitBounds(nctbounds)
    //L.rectangle(nctbounds, {color: "#ff7800", weight: 5}).addTo(map);
    let lat = (nctbounds.getNorth() + nctbounds.getSouth()) / 2;
    let lng = (nctbounds.getEast() + nctbounds.getWest()) / 2;

    function onEachDistrictLayer(feature, layer) {
        layer.bindTooltip('District Name : ' + feature.properties['Dist_2020'], {
            className: 'basintooltip',
            closeButton: false,
            sticky: true,
            offset: L.point(0, -20)
        });
        layer.setStyle({
            fillColor: generateRandomColor(),
            weight: 1,
            color: '#000',
            fillOpacity: 0.5,
            // interactive: true,
        });
        // let label = L.marker(layer.getBounds().getCenter(), {
        //     icon: L.divIcon({
        //       className: 'label',
        //       html: feature.properties.Dist_2020,
        //       iconSize: [100, 40]
        //     })
        //   }).addTo(map);
    }
    let totalseconds = Math.abs(curdatetime.getTime() - new Date().getTime()) / 1000;
    console.log("District Layer loaded " + totalseconds + " seconds!");
});


let blockLayers = L.layerGroup(), selectedBlocks = L.layerGroup();
const blockfile = "./jsons/Pun_Blocksf.json";
$.getJSON(blockfile, function (data) {
    blockLayers = data;
    let totalseconds = Math.abs(curdatetime.getTime() - new Date().getTime()) / 1000;
    console.log("Block Layer loaded " + totalseconds + " seconds!");
});
function onEachBlockLayer(feature, layer) {
    //console.log(feature.geometry);
    //console.log(feature.properties['REGION'].length);
    layer.bindTooltip('Block Name : ' + feature.properties['Block2020'], {
        className: 'basintooltip',
        closeButton: false,
        sticky: true,
        offset: L.point(0, -20)
    });
    layer.setStyle({
        // fillColor: generateRandomColor(),
        weight: 1,
        color: 'yellow',
        fillOpacity: 0,
        // interactive: true,
    });
}

function removeMultipleSelectOptions(selectObject) {
    let index = selectObject.options.length;
    while (index--) {
        selectObject.remove(index);
    }
}
function removeOptions(selectObject) {
    let index = selectObject.options.length;
    while (index--) {
        // selectObject.remove(index);
        if (index != 0) { selectObject.remove(index); }
    }

    let nooptgrp = selectObject.children.length;
    for (let i = 0; i < nooptgrp; i++) {
        let child = selectObject.children[i];
        if (child.tagName === 'OPTGROUP' || child.tagName === 'optgroup') {
            selectObject.removeChild(child);
            nooptgrp--;
            i--;
        }
    }
};
function addOptions(select, data, Code, Name) {
    removeOptions(select);
    //addOptions(objStrategy, jsonStrategy, "strategycode", "strategyname");
    data.forEach(function (dat) {
        let opt = document.createElement('option');
        opt.value = dat[Code];
        opt.innerHTML = Code == 'strategycode' ? dat[Code] + ' - ' + dat[Name] : dat[Name];
        select.appendChild(opt);
    })

    // if (selectedName != '-1') {
    //     for (let i = 0; i < select.options.length; i++) {
    //         if (select.options[i].value == selectedName) {
    //             select.selectedIndex = i;
    //             break;
    //         }
    //     }
    // }
}

let highlight;
function setHighlight(layer) {
    if (highlight) {
        unsetHighlight(highlight);
    }
    layer.setStyle({
        color: 'cyan',
        weight: 4,
    });
    highlight = layer;
}

function unsetHighlight(layer) {
    highlight = null;
    layer.setStyle({
        weight: 1,
        color: '#000',
    });
}
async function fetchData(target, code) {
    try {
        const jsonData = { target: target, code: code };
        const response = await fetch('fetchData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(jsonData)
        });

        if (!response.ok) {
            return "{\"error\":\"Network response was not ok\"}";
            // throw new Error('Network response was not ok');
        }

        return await response.text();
    } catch (error) {
        console.error('There was a problem with your fetch operation:', error);
        return "{\"error\":\"Network response was not ok\"}";
    }
}

function hidemessage() {
    objAlert.classList.remove("show");
    objAlert.classList.add("hide");
}
function showMessage(messagetype, message) {
    // if(!objAlertMessage.classList.contains("show")){
    //     objAlert.classList.add("show");
    // }
    objAlert.classList.remove("hide");
    objAlert.classList.add("show");
    objAlert.classList.add(messagetype);
    objMessage.innerHTML = message;
    const msgInterval = setInterval(function myTimer() {
        hidemessage();
        clearInterval(msgInterval);
      }, 5000);
}
async function changeMission(obj) {
    let selValue = obj.value;
    if (selValue !== '-1') {
        // Add Nodal Agency
        let textNodal = await fetchData('nodal', selValue);
        let cleanedNodal = textNodal.replace(/^"|"$/g, '');
        let jsonNodal = JSON.parse(cleanedNodal);
        // console.log(jsonNodal);
        if (jsonNodal.error === undefined) {
            objNodalAgency.value = jsonNodal.nodalagencyname;
        } else {
            // alert(jsonNodal.error);
            showMessage("alert-danger", jsonNodal.error);
            // objAlert.classList.add("show");
            // objAlert.classList.add("alert-danger");
            // objMessage.innerHTML = jsonNodal.error;
        }

        // Add Strategy
        let textStrategy = await fetchData('strategy', selValue);
        let cleanedStrategy = textStrategy.replace(/^"|"$/g, '');
        let jsonStrategy = JSON.parse(cleanedStrategy);
        // console.log(jsonStrategy);
        if (jsonStrategy.error === undefined) {
            addOptions(objStrategy, jsonStrategy, "strategycode", "strategyname");
        } else {
            showMessage("alert-danger", jsonStrategy.error);
            // alert(jsonStrategy.error);
        }
        removeOptions(objImplementAgency)
        removeOptions(objScheme);

        //objNodalAgency.value = jsonNodal.nodalagencyname;

    } else {
        initializeForm();
    }
}
objMission.addEventListener('change', function () { changeMission(objMission) });

async function changeStrategy(obj) {
    'use strict'

    let selValue = obj.value;
    if (selValue !== '-1') {
        let textOthers = await fetchData('others', selValue);
        // console.log(textOthers);
        let cleanedOthers = textOthers.replace(/^"|"$/g, '');
        // console.log(textOthers);
        let jsonOthers = JSON.parse(cleanedOthers);
        console.log(jsonOthers);
        // console.log(jsonOthers.length);
        // console.log(Object.keys(jsonOthers));
        // console.log(Object.keys(jsonOthers).length);
        /*
        0        :         "implement"
        1        :         "scheme"
        2        :         "categoryaction"
        3        :         "nature"
        4        :         "keyprogressindicators"
        5        :         "categoryindicators"
        */
        // Implementing Agency
        if (jsonOthers.implement.error === undefined) {
            addOptions(objImplementAgency, jsonOthers.implement, "implementingagencycode", "implementingagencyname");
        } else {
            // alert(jsonOthers.categoryaction.error);
            showMessage("alert-danger", jsonOthers.categoryaction.error);
            removeOptions(objImplementAgency);
        }
        // Scheme
        if (jsonOthers.scheme.error === undefined) {
            addOptions(objScheme, jsonOthers.scheme, "schemecode", "schemename");
        } else {
            // alert(jsonOthers.scheme.error);
            showMessage("alert-danger", jsonOthers.scheme.error);
            removeOptions(objScheme);
        }
        // Category of Action
        if (jsonOthers.categoryaction.error === undefined) {
            objClimateAction.value = jsonOthers.categoryaction[0].categoryactioncode;
        } else {
            // alert(jsonOthers.categoryaction.error);
            showMessage("alert-danger", jsonOthers.categoryaction.error);
            objClimateAction.value = "";
        }
        // Nature
        if (jsonOthers.nature.error === undefined) {
            objNature.value = jsonOthers.nature[0].naturename
        } else {
            // alert(jsonOthers.nature.error);
            showMessage("alert-danger", jsonOthers.nature.error);
            objNature.value = "";
        }

        objLinkedSDG.value = "";
        if (jsonOthers.sdg.error === undefined) {
            let sdgvalue = ''
            jsonOthers.sdg.forEach((d) => {
                sdgvalue += d.sdgcode + ", "
            })
            objLinkedSDG.value = sdgvalue.slice(0, -2);
            // addOptions(objLinkedSDG, jsonOthers.sdg, "sdgcode", "sdgcode");
        } else {
            // alert(jsonOthers.sdg.error);
            showMessage("alert-danger", jsonOthers.sdg.error);
        }

        objLinkedNDC.value = "";
        // removeMultipleSelectOptions(objLinkedNDC)
        // Nationally Determined Contributions
        if (jsonOthers.ndc.error === undefined) {
            let ndcvalue = ''
            jsonOthers.ndc.forEach((d) => {
                ndcvalue += d.ndccode + ", "
            })
            objLinkedNDC.value = ndcvalue.slice(0, -2);
            // addOptions(objLinkedNDC, jsonOthers.ndc, "ndccode", "ndccode");
        } else {
            // alert(jsonOthers.ndc.error);
            showMessage("alert-danger", jsonOthers.ndc.error);
        }

        if (document.contains(document.getElementById("kpiTable"))) {
            document.getElementById("kpiTable").remove();
        }
        // else {
        //     lastDiv.appendChild(submitButton);
        // }

        // Key Progress Indicator
        if (jsonOthers.keyprogressindicators.error === undefined) {

            let kpicntr = 1;
            let table = document.createElement('table');
            table.id = "kpiTable";
            table.classList.add('table');

            let trHeader = document.createElement('tr');
            ["Scheme's Performance Indicators", "Indicator's Status", "Unit", "Perforamance Indicator's Category"].forEach((f) => {
                let td = document.createElement('td');
                let text = document.createTextNode(f);
                td.appendChild(text);
                trHeader.appendChild(td);
            })
            table.appendChild(trHeader);

            jsonOthers.keyprogressindicators.forEach((kpi) => {
                console.log(kpi.keyprogressindicatorsname + " : " + kpi.categoryindicatorsname);
                // for (let i = 1; i < 4; i++) {
                let tr = document.createElement('tr');

                let td1 = document.createElement('td');
                let td2 = document.createElement('td');
                let td3 = document.createElement('td');
                let td4 = document.createElement('td');

                //Indicator's Name
                let input1 = document.createElement('input');
                input1.id = 'kpi' + kpicntr;
                input1.name = 'kpi' + kpicntr;
                input1.type = 'text';
                // input1.classList.add("form-control");
                input1.setAttribute('value', kpi.keyprogressindicatorsname);
                input1.setAttribute('title', kpi.keyprogressindicatorsname);

                //Indicator's Status
                let input2 = document.createElement('input');
                input2.id = 'is' + kpicntr;
                input2.name = 'is' + kpicntr;
                input2.type = kpi.cellformat.trim() === "Number" ? 'number' : 'text';
                // input2.classList.add("form-control");
                input2.setAttribute('style', 'width:100px;');
                // class="form-control" 
                // console.log([kpi.cellformat.trim(),kpi.cellformat.trim() === "Number"])

                //Indicator's Unit
                let unit = kpi.units !== "Text Box" ? kpi.units : "";
                let input3 = document.createElement('input');
                input3.id = 'unit' + kpicntr;
                input3.name = 'unit' + kpicntr;
                input3.type = 'text';
                input3.setAttribute('style', 'width:100px;');
                input3.setAttribute('readonly', 'true');
                input3.setAttribute('value', unit);

                // Category Indicators Name
                let input4 = document.createElement('input');
                input4.id = 'ci' + kpicntr;
                input4.name = 'ci' + kpicntr;
                input4.type = 'text';
                input4.setAttribute('value', kpi.categoryindicatorsname);
                input4.setAttribute('style', 'width:100px;');

                td1.appendChild(input1);
                td2.appendChild(input2);
                td3.appendChild(input3);
                td4.appendChild(input4);
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);

                table.appendChild(tr);
                // }
                objNokpi.value = kpicntr;
                kpicntr++;

            })
            // document.body.appendChild(table);
            objKeyProgressIndicator.appendChild(table);
        } else {
            // alert(jsonOthers.keyprogressindicators.error);
            objNokpi.value = 0;
            showMessage("alert-danger", jsonOthers.keyprogressindicators.error);
        }


        // jsonOthers.keyprogressindicators.forEach((kpi) => {
        //     console.log(kpi.keyprogressindicatorsname + " : " + kpi.categoryindicatorsname);
        // })

        // // Category of Indicators
        // if (jsonOthers.categoryindicators.error === undefined) {
        //     addOptions(objCategoryIndicators, jsonOthers.categoryindicators, "categoryindicatorscode", "categoryindicatorsname");
        // } else {
        //     alert(jsonOthers.categoryindicators.error);
        // }
    } else {
        changeImplementAgency();
    }

}
objStrategy.addEventListener('change', function () { changeStrategy(objStrategy) });

// async function changeImplementAgency(obj) {
//     'use strict'
//     let selectedText = obj.options[obj.selectedIndex].text;
//     let selValue = obj.value;

//     console.log([selectedText, selValue])
//     implementagencycode = selValue;
// }
// objImplementAgency.addEventListener('change', function () { changeImplementAgency(objImplementAgency) });

//addOptions(stateSelect, statelist)
// //@@@@@@@@@@@@@@@@@ polygon filter @@@@@@@@@@@@@@@@@@@@@@@@@@
// let picnic_parks = L.geoJson(myJson, { filter: picnicFilter }).addTo(map);
function blockFilter(feature) {
    if (selectedValues.includes(feature.properties.CenCode)) return true;// === districtcode
}
let selectedValues = [];
function changeDistrict(obj) {
    'use strict'
    let selectedText = obj.options[obj.selectedIndex].text;
    let selValue = obj.value;
    console.log([selectedText, selValue])

    if (map.hasLayer(selectedBlocks)) {
        map.removeLayer(selectedBlocks)
    }

    const selectedOptions = obj.selectedOptions;
    selectedValues = [];
    for (let i = 0; i < selectedOptions.length; i++) {
        selectedValues.push(selectedOptions[i].value);
    }

    if (selValue !== '-1') {
        let returnvalue = blocks.filter(function (dv) {
            return selectedValues.includes(dv.CenCode);
        })

        if (returnvalue.length > 0) {
            districtLayers.eachLayer(function (layer) {
                let feat = layer.feature;
                let prop = feat.properties;
                if (selectedValues.includes(prop['CenCode'])) { // == selValue
                    layer.setStyle({
                        color: 'cyan',
                        weight: 4,
                    });
                } else {
                    layer.setStyle({
                        weight: 1,
                        color: '#000',
                    });
                }
            })
            selectedBlocks = L.geoJson(blockLayers, {
                onEachFeature: onEachBlockLayer,
                filter: blockFilter
            }).addTo(map);
            map.fitBounds(selectedBlocks.getBounds());
        }
    } else {
        selectedValues = [];
        if (highlight) {
            unsetHighlight(highlight);
        }
        // removeOptions(objBlock);

        districtLayers.eachLayer(function (layer) {
            layer.setStyle({
                weight: 1,
                color: '#000',
            });
        })
    }
}
objDistrict.addEventListener('change', function () { changeDistrict(objDistrict) });

function changeGeographical(obj) {
    'use strict'
    let selectedText = obj.options[obj.selectedIndex].text;
    let selValue = obj.value;

    console.log([selectedText, selValue])
    // geocode = selValue;

    if (highlight) {
        unsetHighlight(highlight);
    }

    if (selValue === 'district') {
        document.getElementById('divdistrict').style.display = 'inline';
        addOptions(objDistrict, districts, "CenCode", "Dist_2020");
    } else {
        document.getElementById('divdistrict').style.display = 'none';
        removeOptions(objDistrict);
        districtLayers.eachLayer(function (layer) {
            layer.setStyle({
                weight: 1,
                color: '#000',
            });
        })
    }
    // if (selValue === 'block') {
    //     document.getElementById('divdistrict').style.display = 'inline';
    //     addOptions(objDistrict, districts, "CenCode", "Dist_2020");
    // } else {
    //     removeOptions(objBlock);
    // }
}
objGeographical.addEventListener('change', function () { changeGeographical(objGeographical) });

function removeRow(btnName) {
    try {
        let table = document.getElementById('dataTable');
        let rowCount = table.rows.length;
        for (let i = 0; i < rowCount; i++) {
            let row = table.rows[i];
            let rowObj = row.cells[0].childNodes[0];
            if (rowObj.name == btnName) {
                table.deleteRow(i);
                rowCount--;
            }
        }
    } catch (e) {
        console.error("removeRow : " + e);
    }
}
function deleteRow() {
    // let table = document.getElementById('shtable');
    let rowCount = objshTable.rows.length - 2;
    // alert('No of rows : ' + table.rows.length);
    if (rowCount > 0) {
        objshTable.deleteRow(rowCount);
        objNoshare.value = rowCount - 1;
        // alert(document.getElementById('noshare').value);
    }
    return objNoshare.value;
    // let i=row.parentNode.parentNode.rowIndex;
    // document.getElementById('POITable').deleteRow(i);
}
function addRow() {
    // let table = document.getElementById('shtable');
    let rowCount = objshTable.rows.length - 1;
    let row = objshTable.insertRow(rowCount);

    let cell1 = row.insertCell(0);
    let element1 = document.createElement("input");
    element1.type = "text";
    element1.classList.add("form-control");
    let shName = "shname" + (rowCount);
    element1.id = shName;
    element1.name = shName;
    element1.setAttribute('value', '');
    cell1.appendChild(element1);

    let cell2 = row.insertCell(1);
    let element2 = document.createElement("input");
    element2.type = "number";
    element2.classList.add("form-control");
    let shpName = "shpercentage" + (rowCount);
    element2.id = shpName;
    element2.name = shpName;
    // element2.setAttribute('onchange',"isNumber(this)");
    // element2.setAttribute('value', 'Percentage');
    element2.setAttribute('max', '100');
    cell2.appendChild(element2);

    document.getElementById('noshare').value = rowCount;
    // alert(document.getElementById('noshare').value);
}
function changeSource(obj) {
    'use strict'
    let selectedText = obj.options[obj.selectedIndex].text;
    let selValue = obj.value;

    // console.log([selectedText, selValue])
    //implementagencycode = selValue;
    if (selValue === 'Others' || selValue === 'Centrally Sponsored Scheme') {
        objtbSource.style.display = 'inline';
        // } else if (selValue === 'Centrally Sponsored Scheme') {
        //     objtbSource.style.display = 'inline';
    } else {
        objtbSource.style.display = 'none';
        let norows = parseInt(objNoshare.value.trim());
        if (norows > 0) {
            for (let r = 0; r < norows; r++) {
                deleteRow();
            }
        }
    }
}

objSourceFunding.addEventListener('change', function () { changeSource(objSourceFunding) });
// objtbSource.addEventListener('change', function () { changeSource(objtbSource) });
// function changeShare(obj) {
//     'use strict'
//     let selectedText = obj.options[obj.selectedIndex].text;
//     let selValue = obj.value;
//     // console.log([selectedText, selValue])
//     //implementagencycode = selValue;
//     if (selValue === 'Others') {
//         objtbShare.style.display = 'inline';
//     } else {
//         objtbShare.style.display = 'none';
//     }
// }
// objShare.addEventListener('change', function () { changeShare(objShare) });
// objBlock.addEventListener('change', function () { changeBlock(objBlock) });

// for (let year = new Date().getFullYear(); year > 2000; year--) {
//     let opt = document.createElement('option');
//     opt.value = year;
//     opt.innerHTML = year;
//     objYearofLaunch.appendChild(opt);
// }
for (let year = new Date().getFullYear(); year > 2000; year--) {
    let opt = document.createElement('option');
    opt.value = year + '-' + (year + 1);
    opt.innerHTML = year + '-' + (year + 1);
    objFinancialYear.appendChild(opt);
}

(function () {
    let control = new L.Control({ position: 'topleft' });
    control.onAdd = function (map) {
        let azoom = L.DomUtil.create('div', 'leaflet-control-zoom leaflet-bar leaflet-control');
        azoom.innerHTML = '<a href=# class="leaflet-control-zoom-out" title="Reset"><img src="images/home-1_16px.png" width="18" height="18" style="display:inline; margin-top:4px; margin-right:1px;" alt="Reset" /></a>';
        L.DomEvent
            .disableClickPropagation(azoom)
            .addListener(azoom, 'click', function () {
                if (!jQuery.isEmptyObject(districtLayers) && typeof districtLayers !== 'undefined' || districtLayers !== null)
                    map.fitBounds(districtLayers.getBounds())
            }, azoom);
        return azoom;
    };
    return control;
}()).addTo(map);

function handleChange() {
    const inputValue = document.getElementById("tbsource").value;
    console.log("Input changed to:", inputValue);
}

function isNumber(objElement) {

    if (objElement.value.trim() == "") return;
    // const value = this.value;
    // let Expenditure = parseInt(objActualExpenditure.value.trim());
    let value = parseFloat(objElement.value.trim());
    objElement.value = value;
    if (!value || isNaN(value)) {
        // alert("Please enter numbers only : " + objElement.name);
        showMessage("alert-warning", "Please enter numbers only");
        objElement.value = parseFloat(objElement.value.trim());
        // objActualExpenditure.focus();
        // return false;
    } else {
        changeWeightage();
    }
    // return true;
    // else {
    //     actualExpenditureTowards.value = (Expenditure * (Weightage / 100)).toFixed(2);
    //     // actualExpenditureTowards.setCustomValidity('');
    // }
}

function changeWeightage(objElement) {

    // if (objElement.value.trim() == "") {
    //     alert("Please enter Weightage between 0 and 100");
    //     return;
    // }
    // let Weightage = parseInt(objElement.value.trim());
    // if (!Weightage || isNaN(Weightage) || Weightage < 0 || Weightage > 100) {
    //     alert("Please enter Weightage between 0 and 100");
    //     objElement.value = '';
    //     // objElement.focus();
    // }
    objExpenditureTowardsClimate.value = '';
    if (objActualExpenditure.value.trim() == "") return;
    if (objWeightage.value.trim() == "") return;

    let Expenditure = parseFloat(objActualExpenditure.value.trim());
    objActualExpenditure.value = Expenditure;
    let Weightage = parseFloat(objWeightage.value.trim());
    objWeightage.value = Weightage;

    // console.log([Expenditure,Weightage]);

    if (objAllocatedBudget.value.trim() !== '') {
        // let budgetAllocated = parseInt(objAllocatedBudget.value.trim());
        let budgetAllocated = parseFloat(objAllocatedBudget.value.trim());
        objAllocatedBudget.value = budgetAllocated;
        if (Expenditure > budgetAllocated) {
            // alert("Scheme's Actual Expenditure cannot be more than Scheme's Allocated Budget");
            showMessage("alert-warning", "Scheme's Actual Expenditure cannot be more than Scheme's Allocated Budget");
            objActualExpenditure.value = "";
            return;
        }
    }

    if (!Weightage || isNaN(Weightage) || Weightage < 0 || Weightage > 100) {
        // alert("Weightage Towards Scheme's Climate Action (In %)\nshould be between 0 and 100");
        showMessage("alert-warning", "Weightage Towards Scheme's Climate Action (In %)\nshould be > 0 and ≤ 100.");
        objWeightage.value = '';
        objWeightage.focus();
    }
    else {
        //ExpenditureTowardsClimate
        objExpenditureTowardsClimate.value = (Expenditure * (Weightage / 100.0)).toFixed(2);
        // actualExpenditureTowards.setCustomValidity('');
    }
}

function getSelectedValues() {

    const selectElement = document.getElementById("mySelect");
    if (selectElement.hasAttribute("multiple")) {
        console.log("Multiple selection allowed!");
    } else {
        console.log("Only single selection allowed.");
    }

    //    const selectElement = document.getElementById("mySelect");
    const selectedOptions = selectElement.selectedOptions;

    // Loop through selected options and get their values
    let selectedValues = [];
    for (let i = 0; i < selectedOptions.length; i++) {
        selectedValues.push(selectedOptions[i].value);
    }

    console.log("Selected values:", selectedValues);
}

// function compareByName(a, b) {
//     // Ascending order
//     return a.AgencyName > b.AgencyName ? 1 : -1;
//     // Decending order
//     // return a.AgencyName < b.AgencyName ? 1 : -1;
// }

// const compareByName  = (a, b) =>a.AgencyName > b.AgencyName ? 1 : -1;
// nodalagencies.sort(compareByName);
// console.log(nodalagencies);

function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (err) {
        return false;
    }
}


function validateForm() {
    // let divElem = document.getElementById("myDiv");
    // let inputElements = divElem.querySelectorAll("input, select, checkbox, textarea");
    // let inputElements = objtbSource.querySelectorAll("input, select, checkbox, textarea");
    // console.log(inputElements);

    if (objMission.value == "-1") {
        showMessage("alert-warning", "Please select National Mission");

        objMission.focus();
        return false;
    }

    if (objNodalAgency.value.trim() == "") { //objCategoryAction
        showMessage("alert-warning", "Please check Nodal Agency not mention");
        objNodalAgency.focus();
        return false;
    }
    if (objStrategy.value.trim() == "") {
        showMessage("alert-warning", "Please select Strategy");
        objStrategy.focus();
        return false;
    }

    if (objImplementAgency.value == "-1") {
        showMessage("alert-warning", "Please select Implementing Agency");
        objImplementAgency.focus();
        return false;
    }

    if (objScheme.value == "-1") {
        showMessage("alert-warning", "Please select Scheme");
        objScheme.focus();
        return false;
    }

    if (objGeographical.value == "-1") {
        showMessage("alert-warning", "Please select Geographical Coverage");

        objGeographical.focus();
        return false;
    } else if (objGeographical.value == 'district') {
        if (objDistrict.value == "-1") {
            showMessage("alert-warning", "Please select District");

            objDistrict.focus();
            return false;
        } else {
            console.log('============ district list =================');
            let options = objDistrict.selectedOptions;
            let texts = Array.from(options).map(({ text }) => text);
            document.getElementById('selectedDistrict').value = texts.join(", ");

            // for (let o = 0; o < objDistrict.selectedOptions.length; o++) {
            //     console.log([objDistrict.selectedOptions[o].value, objDistrict.selectedOptions[o].text]);
            // }

            // let options = objDistrict.selectedOptions;
            // let values = Array.from(options).map(({ value }) => value);
            // console.log(values);

            // let texts = Array.from(options).map(({ text }) => text);
            // console.log(texts.join(", "));   
            // fruits.join(" and ");    

        }
    }

    if (objSourceFunding.value == "-1") {
        showMessage("alert-warning", "Please select Source of Funding");
        objSourceFunding.focus();
        return false;
    } else if (objSourceFunding.value == "Centrally Sponsored Scheme" || objSourceFunding.value == "Others") {
        let norows = objshTable.rows.length;
        if (norows <= 2 || objNoshare.value == "") {
            showMessage("alert-warning", "Please add Shareholders");
            return false;
        } else {
            let noshareholders = parseInt(objNoshare.value);
            let totpercentage = 0;
            let isEmptyField = false;
            for (let r = 1; r <= noshareholders; r++) {
                let shname = document.getElementById('shname' + r).value;
                if (shname == "") {
                    isEmptyField = true;
                }
                let shprtg = document.getElementById('shpercentage' + r).value;
                if (shprtg !== "") {
                    shprtg = parseFloat(shprtg);
                } else {
                    isEmptyField = true;
                    shprtg = 0;
                }
                totpercentage += shprtg;
                console.log(shname + " : " + shprtg + " : " + totpercentage + " : " + r);
            }

            if (isEmptyField) {
                showMessage("alert-warning", "Shareholder and Percentage con't be Empty or Zero");
                return false;
            } else if (totpercentage > 100) {
                showMessage("alert-warning", "Shareholders Percentage should be > 0 and ≤ 100.");
                return false;
            }
        }
        // showMessage("alert-warning", "");
    }

    if (objFinancialYear.value == "-1") {
        showMessage("alert-warning", "Please select Financial Year");
        objFinancialYear.focus();
        return false;
    }

    if (objAllocatedBudget.value.trim() == "") {
        showMessage("alert-warning", "Enter Allocated Budget");
        objAllocatedBudget.focus();
        return false;
    } else if (isNumber(objAllocatedBudget)) {
        showMessage("alert-warning", "Enter valid number in Allocated Budget");
        objAllocatedBudget.focus();
        return false;
    }

    if (objActualExpenditure.value.trim() == "") {
        showMessage("alert-warning", "Enter Actual Expenditure");
        objActualExpenditure.focus();
        return false;
    } else if (isNumber(objActualExpenditure)) {
        showMessage("alert-warning", "Enter valid number in Actual Expenditure");
        objActualExpenditure.focus();
        return false;
    }

    if (objExpenditureTowardsClimate.value.trim() == "") {
        showMessage("alert-warning", "Enter Expenditure Towards Climate");
        objExpenditureTowardsClimate.focus();
        return false;
    } else if (isNumber(objExpenditureTowardsClimate)) {
        showMessage("alert-warning", "Enter valid number in Expenditure Towards Climate");
        objExpenditureTowardsClimate.focus();
        return false;
    }

    if (objWeightage.value.trim() == "") {
        showMessage("alert-warning", "Weightage is not been calculated");
        objWeightage.focus();
        return false;
    } else if (isNumber(objWeightage)) {
        showMessage("alert-warning", "Not a valid number in Weightage");
        objWeightage.focus();
        return false;
    }
    else {
        let wtgPrecentage = parseFloat(objWeightage.value);
        if (wtgPrecentage < 0 || wtgPrecentage > 100) {
            // console.log(wtgPrecentage);
            showMessage("alert-warning", "Enter Weightage > 0 and ≤ 100.");
            return false;
        }
    }

    let nokpi = parseInt(objNokpi.value);
    if (nokpi > 0) {
        let isEmptyField = false;
        for (let r = 1; r <= nokpi; r++) {
            // input2.id = 'is' + kpicntr;
            let kpistatus = document.getElementById('is' + r).value;
            if (kpistatus == "" || kpistatus == undefined) {
                isEmptyField = true;
            }
        }
        if (isEmptyField) {
            showMessage("alert-warning", "Indicator's Status con't be empty in Key Progress Indicator");
            return false;
        }
    } else {
        showMessage("alert-warning", "Key Progress Indicator does not exists");
        return false;
    }

    document.getElementById("progressbar").style.display = 'block';
    submitForm();

    return false;

}

function submitForm() {
    const form = document.getElementById('budget-form');
    const formData = new FormData(form);

    fetch('AddBudget.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                document.getElementById("progressbar").style.display = 'none';
                // document.getElementById('alert-header').innerText = 'Not Updated/Deleted';
                // document.getElementById('alert-box').style.display = 'block';

                showMessage("alert-danger", "Not added, please contact Admin");

                // throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById("progressbar").style.display = 'none';
            // document.getElementById('alert-header').innerText = data;
            // document.getElementById('alert-box').style.display = 'block';
            // console.log(data); // Handle the response data
            console.log(data);
            if (data.includes('Successfully')) {
                showMessage("alert-success", data);
                initializeForm();
            } else {
                showMessage("alert-danger", data);
            }
        })
        .catch(error => {
            document.getElementById("progressbar").style.display = 'none';
            // document.getElementById('alert-header').innerText = 'Not Updated/Deleted';
            // document.getElementById('alert-box').style.display = 'block';
            showMessage("alert-danger", "Not added, please contact Admin");
            console.error('There was a problem with the fetch operation:', error);
        });
    return false;
}


// objAlertMessage.classList.add("show");
// objAlertMessage.classList.add("hide");
// objAlertMessage.classList.add("alert-warning", "alert-success", "alert-danger");
// objAlertMessage.classList.remove("animate");
// objAlertMessage.classList.remove("animate", "hide");
// objAlertMessage.classList.contains("animate");
//showMessage("alert-warning", "Please select Scheme");
