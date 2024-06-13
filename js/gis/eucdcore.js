'use strict'

let curdatetime = new Date();
let zoom = 4.0;
const colorRange = ['#cf7834', '#7347cb', '#39ab3d', '#ce4ec6', '#75a03c', '#d23e73', '#42a181', '#d8412f', '#6378c2'];
const flclr = ['#fb6a4a', '#ef3b2c', '#cb181d', '#a50f15', '#67000d'];

let filteredDepartment = [], filteredImplAgency = [], filteredScheme = [], filteredMission = [];
let filteredData = [], filteredImplAgencyData = [], filteredSchemeData = [], filteredMissionData = [];

const objMission = document.getElementById('mission');
const objImplementAgency = document.getElementById('ImplementAgency');
const objDepartment = document.getElementById('department');
const objScheme = document.getElementById('scheme');
const objDistrict = document.getElementById('district');
const objBlock = document.getElementById('block');
const objNodalAgency = document.getElementById('NodalAgency');
const objGeographical = document.getElementById('geographical');
const objYearofLaunch = document.getElementById('YearofLaunch');
const objFinancialYear = document.getElementById('FinancialYear');
const objSource = document.getElementById('source');
const objtbSource = document.getElementById('tbsource');
const objTypeofMeasure = document.getElementById('TypeofMeasure');
const objtbShare = document.getElementById('tbshare');

//let districtLabelGroup = L.layerGroup();
// console.log(objScheme)
let implementagencycode = '-1', geocode = '-1', missioncode = '-1', schemcode = '-1', districtcode = '-1', blockcode = '-1', clusterName = '-1', departmentcode = '-1';

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
        // var label = L.marker(layer.getBounds().getCenter(), {
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

    data.forEach(function (dat) {
        let opt = document.createElement('option');
        opt.value = dat[Code];
        opt.innerHTML = dat[Name];
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

async function changeMission(obj) {
    'use strict'
    let selectedText = obj.options[obj.selectedIndex].text;
    let selValue = obj.value;

    console.log([selectedText, selValue])
    missioncode = selValue;

    filteredData = [];
    if (selValue !== '-1') {
        // console.log(nodalagencies);
        filteredData = lookupschememission.filter(function (dv) {
            return Number(dv.MissionCode) === Number(selValue);
        })
        if (filteredData.length > 0) {
            let agencyCode = filteredData[0].NodalAgencyCode
            let returnLookupNodelAgency = nodalagencies.filter(function (dv) {
                return Number(dv.NodalAgencyCode) === Number(agencyCode);
            })
            if (returnLookupNodelAgency.length > 0) {
                // console.log(returnLookupNodelAgency[0].NodalAgencyName);
                objNodalAgency.value = returnLookupNodelAgency[0].NodalAgencyName;
            } else {
                objNodalAgency.value = "";
            }
            let impAgencies = filteredData.map(a => a.ImplementAgencyCode);
            // console.log(impAgencies);
            let returnLookupImplementAgency = implementagencies.filter(function (dv) {
                return impAgencies.includes(dv.ImplementingAgencyCode);
            })
            // await filterDepartment(selValue);
            // console.log(returnLookupImplementAgency);
            if (returnLookupImplementAgency.length > 0) {
                addOptions(objImplementAgency, returnLookupImplementAgency, "ImplementingAgencyCode", "ImplementingAgencyName");
            }
        }
    } else {
        objNodalAgency.value = "";
        removeOptions(objImplementAgency);
    }
    removeOptions(objScheme);
    removeOptions(objDepartment);
}
objMission.addEventListener('change', function () { changeMission(objMission) });

async function changeImplementAgency(obj) {
    'use strict'
    let selectedText = obj.options[obj.selectedIndex].text;
    let selValue = obj.value;

    console.log([selectedText, selValue])
    implementagencycode = selValue;

    if (selValue !== '-1') {
        // console.log(filteredData);
        let filteredImplementAgency = filteredData.filter(function (dv) {
            // console.log([Number(dv.ImplementAgencyCode), Number(selValue)])
            return Number(dv.ImplementAgencyCode) === Number(selValue);
        })
        // console.log(filteredData);
        if (filteredImplementAgency.length > 0) {
            let departmentCodes = filteredImplementAgency.map(a => a.DepartmentCode);
            let returnDepartmentData = departments.filter(function (dv) {
                return departmentCodes.includes(dv.DepartmentCode);
            })
            if (returnDepartmentData.length > 0) {
                addOptions(objDepartment, returnDepartmentData, "DepartmentCode", "DepartmentName");
            }
        }
    } else {
        removeOptions(objDepartment);
    }
    removeOptions(objScheme);
}
objImplementAgency.addEventListener('change', function () { changeImplementAgency(objImplementAgency) });

async function changeDepartment(obj) {
    'use strict'
    let selectedText = obj.options[obj.selectedIndex].text;
    let selValue = obj.value;

    console.log([selectedText, selValue])
    departmentcode = selValue;

    if (selValue !== '-1') {

        let returnSchemeData = schemes.filter(function (dv) {
            return Number(dv.DepartmentCode) == Number(selValue);
        })
        // console.log(returnSchemeData);
        if (returnSchemeData.length > 0) {
            addOptions(objScheme, returnSchemeData, "SchemeCode", "SchemeName");
        } else {
            alert('No Scheme exists');
        }
    } else {
        removeOptions(objScheme);
    }

}
objDepartment.addEventListener('change', function () { changeDepartment(objDepartment) });

async function addOptionsBlocks(select, data, Code, Name) {// , 
    removeOptions(select);

    // for (let i = 0; i < select.children.length; i++) {
    //     let child = select.children[i];
    //     if (child.tagName === 'OPTGROUP' || child.tagName === 'optgroup') {
    //         select.removeChild(child);
    //         i--;
    //         // break;
    //     }
    // }

    // console.log(data);
    // //let impAgencies = filteredData.map(a => a.ImplementAgencyCode);
    // let districtcodes = new Set();//data.map(m => m.CenCode);
    // data.forEach((a)=>{
    //     districtcodes.add(a.CenCode);
    //     console.log(a.CenCode);
    // })
    // let districtcodes;// = data.map(m => m.CenCode);
    // console.log(districtcodes);
    let districtcodes = [...new Set(data.map(m => m.CenCode))];
    console.log(districtcodes);

    districtcodes.forEach((dc) => {
        let districtname = districts.filter((f) => f.CenCode === dc)[0].Dist_2020
        // console.log(districtname);
        let optgrp = document.createElement('optgroup');
        optgrp.setAttribute("label", districtname);
        // optgrp.value = dc;
        // optgrp.innerHTML = districtname;
        let retunblocks = data.filter((f) => f.CenCode === dc).forEach(function (dat) {
            let opt = document.createElement('option');
            opt.value = dat[Code];
            opt.innerHTML = dat[Name];
            optgrp.appendChild(opt);
        });

        select.appendChild(optgrp);
        // console.log(retunblocks);
        // if (retunblocks.length > 0) {
        //     retunblocks.forEach(function (dat) {
        //         let opt = document.createElement('option');
        //         opt.value = dat[Code];
        //         opt.innerHTML = dat[Name];
        //         select.appendChild(opt);
        //     })
        // }

    })

}
//addOptions(stateSelect, statelist)
// //@@@@@@@@@@@@@@@@@ polygon filter @@@@@@@@@@@@@@@@@@@@@@@@@@
// var picnic_parks = L.geoJson(myJson, { filter: picnicFilter }).addTo(map);
function blockFilter(feature) {
    if (selectedValues.includes(feature.properties.CenCode)) return true;// === districtcode
}
let selectedValues = [];
function changeDistrict(obj) {
    'use strict'
    let selectedText = obj.options[obj.selectedIndex].text;
    let selValue = obj.value;
    console.log([selectedText, selValue])

    // districtcode = selValue;

    if (map.hasLayer(selectedBlocks)) {
        map.removeLayer(selectedBlocks)
    }

    // const selectElement = document.getElementById("mySelect");
    // if (selectElement.hasAttribute("multiple")) {
    //     console.log("Multiple selection allowed!");
    // } else {
    //     console.log("Only single selection allowed.");
    // }

    //    const selectElement = document.getElementById("mySelect");
    const selectedOptions = obj.selectedOptions;
    selectedValues = [];
    for (let i = 0; i < selectedOptions.length; i++) {
        selectedValues.push(selectedOptions[i].value);
    }
    // console.log("Selected Districts :", selectedValues);
    if (selValue !== '-1') {
        let returnvalue = blocks.filter(function (dv) {
            return selectedValues.includes(dv.CenCode);
            //return dv.CenCode === districtcode;
        })

        // console.log(returnvalue);

        if (returnvalue.length > 0) {
            addOptionsBlocks(objBlock, returnvalue, "Block2020", "Block2020");
            //addOptions(objBlock, returnvalue, "Block2020", "Block2020")
            districtLayers.eachLayer(function (layer) {
                let feat = layer.feature;
                let prop = feat.properties;
                if (selectedValues.includes(prop['CenCode'])) { // == selValue
                    // map.fitBounds(layer.getBounds());
                    //setHighlight(layer);
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
                // style: function (feature) {
                //     return {
                //         weight: 1,
                //         fillColor: generateRandomColor(),
                //         color: '#000',
                //         fillOpacity: 0.5,
                //     }
                // }, 
                filter: blockFilter
            }).addTo(map);
            map.fitBounds(selectedBlocks.getBounds());
        }
    } else {
        selectedValues = [];
        if (highlight) {
            unsetHighlight(highlight);
        }
        removeOptions(objBlock);

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
    geocode = selValue;

    if (highlight) {
        unsetHighlight(highlight);
    }

    // if (selValue !== 'punjab') {
    //     document.getElementById('divdistrict').style.display = 'inline';
    //     addOptions(objDistrict, districts, "CenCode", "Dist_2020");
    // } else {
    //     if (highlight) {
    //         unsetHighlight(highlight);
    //     }
    //     document.getElementById('divdistrict').style.display = 'none';
    //     removeOptions(objDistrict);
    // }

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
    if (selValue === 'block') {

        document.getElementById('divdistrict').style.display = 'inline';
        addOptions(objDistrict, districts, "CenCode", "Dist_2020");

        document.getElementById('divblock').style.display = 'inline';
        addOptions(objBlock, blocks, "CenCode", "Block2020");
        // let returnDistrictValue = blocks.filter(function (dv) {
        //     return Number(dv.MissionCode) === Number(selValue);
        // })
        // if (returnDistrictValue.length > 0) {
        //     addOptions(objDistrict, returnDistrictValue, "CenCode", "Dist_2020");
        // }
    } else {
        document.getElementById('divblock').style.display = 'none';
        removeOptions(objBlock);
    }
}
objGeographical.addEventListener('change', function () { changeGeographical(objGeographical) });

function removeRow(btnName) {
    try {
        var table = document.getElementById('dataTable');
        var rowCount = table.rows.length;
        for (var i = 0; i < rowCount; i++) {
            var row = table.rows[i];
            var rowObj = row.cells[0].childNodes[0];
            if (rowObj.name == btnName) {
                table.deleteRow(i);
                rowCount--;
            }
        }
    } catch (e) {
        alert(e);
    }
}

function addRow() {
    let table = document.getElementById('shtable');
    let rowCount = table.rows.length - 1;
    let row = table.insertRow(rowCount);
    //Column 1  
    let cell1 = row.insertCell(0);
    let element1 = document.createElement("input");
    element1.type = "text";
    element1.classList.add("form-control");
    let shName = "shname" + (rowCount + 1);
    element1.id = shName;
    element1.name = shName;
    element1.setAttribute('value', 'Name'); // or element1.value = "button";  
    // element1.onclick = function() {  
    //     removeRow(btnName);  
    // }  
    cell1.appendChild(element1);
    // //Column 2  
    // var cell2 = row.insertCell(1);  
    // cell2.innerHTML = rowCount + 1;  
    // //Column 3  
    let cell2 = row.insertCell(1);
    let element2 = document.createElement("input");
    element2.type = "number";
    element2.classList.add("form-control");
    let shpName = "shpercentage" + (rowCount + 1);
    element2.id = shpName;
    element2.name = shpName;
    // element2.setAttribute('value', 'Percentage');
    element2.setAttribute('max', '100');
    cell2.appendChild(element2);
}
function changeSource(obj) {
    'use strict'
    let selectedText = obj.options[obj.selectedIndex].text;
    let selValue = obj.value;

    console.log([selectedText, selValue])
    //implementagencycode = selValue;
    if (selValue === 'Others') {
        objtbSource.style.display = 'inline';
    } else if (selValue === 'Centrally Sponsored Scheme') {
        objtbSource.style.display = 'inline';
    } else {
        objtbSource.style.display = 'none';
    }
}

function changeShare(obj) {
    'use strict'
    let selectedText = obj.options[obj.selectedIndex].text;
    let selValue = obj.value;

    console.log([selectedText, selValue])
    //implementagencycode = selValue;
    if (selValue === 'Others') {
        objtbShare.style.display = 'inline';
    } else {
        objtbShare.style.display = 'none';
    }
}

objSource.addEventListener('change', function () { changeSource(objSource) });
// objShare.addEventListener('change', function () { changeShare(objShare) });

// objBlock.addEventListener('change', function () { changeBlock(objBlock) });

for (var year = new Date().getFullYear(); year > 2000; year--) {
    let opt = document.createElement('option');
    opt.value = year;
    opt.innerHTML = year;
    objYearofLaunch.appendChild(opt);
}
for (var year = new Date().getFullYear(); year > 2000; year--) {
    let opt = document.createElement('option');
    opt.value = year + '-' + (year + 1);
    opt.innerHTML = year + '-' + (year + 1);
    objFinancialYear.appendChild(opt);
}

(function () {
    var control = new L.Control({ position: 'topleft' });
    control.onAdd = function (map) {
        var azoom = L.DomUtil.create('div', 'leaflet-control-zoom leaflet-bar leaflet-control');
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

function changeExpenditure() {
    // const inputValue = document.getElementById("tbsource").value;
    // console.log("Input changed to:", inputValue);

    let actualExpenditure = document.getElementById("ActualExpenditure");
    if (actualExpenditure.value.trim() == "") return;
    let consideredWeightage = document.getElementById("ConsideredWeightage");
    if (consideredWeightage.value.trim() == "") return;

    let Expenditure = parseInt(actualExpenditure.value.trim());
    let Weightage = parseInt(consideredWeightage.value.trim());
    let actualExpenditureTowards = document.getElementById("ActualExpenditureTowards");

    if (!Weightage || isNaN(Weightage) || Weightage < 0 || Weightage > 100) {
        alert("Please enter Considered Weightage should be a number between 0 and 100");
        Weightage.value = '';
        consideredWeightage.focus();
        // consideredWeightage.select();
    } else {
        actualExpenditureTowards.value = (Expenditure * (Weightage / 100)).toFixed(2);
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
    let isValid = true;

    // var divElem = document.getElementById("myDiv");
    // var inputElements = divElem.querySelectorAll("input, select, checkbox, textarea");

    var inputElements = objtbSource.querySelectorAll("input, select, checkbox, textarea");
    console.log(inputElements);

    // document.getElementById(frmObj.id).focus();
    // document.getElementById(frmObj.id).select();
    // $("#textboxID").focus();

    // Check if Associated National Missions is selected
    // let mission = document.getElementById("mission");
    if (objMission.value == "-1") {
        alert("Please select National Mission");
        isValid = false;
        objMission.focus();
        // objMission.select();
        return;
    }

    // Check if Nodal Agency is filled out
    // let nodalAgency = document.getElementById("NodalAgency");
    if (objNodalAgency.value.trim() == "") {
        alert("Please enter Nodal Agency");
        isValid = false;
        objNodalAgency.focus();
        // objNodalAgency.select();
        return;
    }

    // Check if Select Implement Agency is selected
    // let implementAgency = document.getElementById("ImplementAgency");
    if (objImplementAgency.value == "-1") {
        alert("Please select Implementing Agency");
        isValid = false;
        objImplementAgency.focus();
        // objImplementAgency.select();
        return;
    }

    // Check if Select Department is selected
    // let department = document.getElementById("department");
    if (objDepartment.value == "-1") {
        alert("Please select Department");
        isValid = false;
        objDepartment.focus();
        // objDepartment.select();
        return;
    }

    // Check if Select Scheme is selected
    // let scheme = document.getElementById("scheme");
    if (objScheme.value == "-1") {
        alert("Please select Scheme");
        isValid = false;
        objScheme.focus();
        // objScheme.select();
        return;
    }

    // Check if Year of Scheme Launch is selected
    let yearOfLaunch = document.getElementById("YearofLaunch");
    if (yearOfLaunch.value == "-1") {
        alert("Please select Year of Scheme Launch");
        isValid = false;
        yearOfLaunch.focus();
        // yearOfLaunch.select();
        return;
    }

    // Check if Source of Funding is selected
    // let source = document.getElementById("source");
    if (objSource.value == "-1") {
        alert("Please select Source of Funding");
        isValid = false;
        objSource.focus();
        // objSource.select();
        return;
    }


    if (objTypeofMeasure.value == "-1") {
        alert("Please select Type of Measure");
        isValid = false;
        objTypeofMeasure.focus();
        // objTypeofMeasure.select();
        return;
    }

    // // Check if Cost Sharing Arrangement is selected
    // // let share = document.getElementById("share");
    // if (objShare.value == "-1") {
    //     alert("Please select Cost Sharing Arrangement");
    //     isValid = false;
    //     objShare.focus();
    //     objShare.select();
    //     return;
    // }

    // Check if Brief Description is filled out
    let briefDescription = document.getElementById("BriefDescription");
    if (briefDescription.value.trim() == "") {
        alert("Please enter Brief Description");
        isValid = false;
        briefDescription.focus();
        // briefDescription.select();
        return;
    }

    // Check if Key Activities is filled out
    let keyActivities = document.getElementById("KeyActivities");
    if (keyActivities.value.trim() == "") {
        alert("Please enter Key Activities");
        isValid = false;
        keyActivities.focus();
        // keyActivities.select();
        return;
    }

    // Check if Geographical Coverage is selected
    let geographical = document.getElementById("geographical");
    if (geographical.value == "-1") {
        alert("Please select Geographical Coverage");
        isValid = false;
        geographical.focus();
        // geographical.select();
        return;
    }

    // Check if Financial Year is selected
    // let financialYear = document.getElementById("FinancialYear");
    if (objFinancialYear.value == "-1") {
        alert("Please select Financial Year");
        isValid = false;
        objFinancialYear.focus();
        // objFinancialYear.select();
        return;
    }

    // Check if Actual Expenditure is filled out
    let actualExpenditure = document.getElementById("ActualExpenditure");
    if (actualExpenditure.value.trim() == "") {
        alert("Please enter Actual Expenditure");
        isValid = false;
        actualExpenditure.focus();
        // actualExpenditure.select();
        return;
    }

    // Check if Considered Weightage is filled out
    let consideredWeightage = document.getElementById("ConsideredWeightage");
    if (consideredWeightage.value.trim() == "") {
        alert("Please enter Considered Weightage");
        isValid = false;
        consideredWeightage.focus();
        // consideredWeightage.select();
        return;
    } else if (isValid === true) {
        let Expenditure = parseInt(actualExpenditure.value.trim());
        let Weightage = parseInt(consideredWeightage.value.trim());
        if (Weightage > 100) {
            alert("Please enter Considered Weightage should be lessthan or equal to 100");
            isValid = false;
            consideredWeightage.focus();
            // consideredWeightage.select();
            return;
        } else {
            let actualExpenditureTowards = document.getElementById("ActualExpenditureTowards");
            actualExpenditureTowards.value = (Expenditure * (Weightage / 100)).toFixed(2);
        }
    }

    // Check if website url is valid
    let schemeWebsite = document.getElementById("website");
    if (schemeWebsite.value.trim() !== "") {
        if (!isValidUrl(schemeWebsite.value.trim())) {
            alert("Please enter valid Scheme's Website");
            isValid = false;
            schemeWebsite.focus();
            // schemeWebsite.select();
            return;
        }
    }
    // // Check if Actual Expenditure Towards Climate Action is filled out
    // let actualExpenditureTowards = document.getElementById("ActualExpenditureTowards");
    // if (actualExpenditureTowards.value.trim() == "") {
    //     alert("Please enter Actual Expenditure Towards Climate Action");
    //     isValid = false;
    // }

    // Check if Performance Towards Climate Action is selected
    let performance = document.getElementById("Performance");
    if (performance.value == "-1") {
        alert("Please select Performance Towards Climate Action");
        isValid = false;
        performance.focus();
        // performance.select();
        return;
    }

    return isValid;
}
// console.log(missions);
addOptions(objMission, missions, "MissionCode", "MissionName");

// document.getElementById("myForm").addEventListener("submit", function(event) {
//     var isValid = true;

//     // Validate each field here
//     // Example:
//     var mission = document.getElementById("mission").value;
//     if (mission == "-1") {
//         isValid = false;
//         alert("Please select a mission.");
//     }

//     // Add more validation for other fields

//     if (!isValid) {
//         event.preventDefault(); // Prevent form submission if validation fails
//     }
// });



// // Create an empty object
// let person = {};

// // Add properties to the object
// person.firstName = "John";
// person.lastName = "Doe";
// person.age = 30;
// person.isStudent = false;

// // Convert the object to JSON
// let json1 = JSON.stringify(person);

// console.log(json1);

// let records = [];
// records.push(person);
// records.push(person);
// records.push(person);
// records.push(person);
// records.push(person);

// console.log(records);
// // Convert the object to JSON
// let json = JSON.stringify(records);

// console.log(json);

// function removeSpecialCharacters(text) {
//     // Replace all non-alphanumeric characters with an empty string
//     return text.replace(/[^a-zA-Z0-9\s\,\&]/g, '');
// }

// // Example usage
// let originalText = 'Hello, and & @World!';
// let sanitizedText = removeSpecialCharacters(originalText);
// console.log(sanitizedText); // Outputs: HelloWorld

function removeSpecialCharacters(text) {
    // Replace '," and @ with an empty string
    return text.replace(/['"@]/g, '');
}

// Example usage
let originalText = 'Hello, @World!';
let sanitizedText = removeSpecialCharacters(originalText);
console.log(sanitizedText); // Outputs: Hello World


// @@@@@@@@@@ Add options @@@@@@@@@@@@
var select = document.getElementById('mySelect');
var option = document.createElement('option');
option.value = 'option_value';
option.text = 'Option Text';
select.appendChild(option);
// @@@@@@@@@@ Remove options @@@@@@@@@@@@
var select = document.getElementById('mySelect');
select.remove(index);
// @@@@@@@@@@ Remove options By Reference @@@@@@@@@@@@
var select = document.getElementById('mySelect');
var optionToRemove = select.options[index];
select.removeChild(optionToRemove);

async function fetchData() {
    var userInput = document.getElementById('myInput').value;
    var sanitizedInput = userInput.replace(/[^a-z0-9\s\-_\.]/gi, ''); // Allow alphanumeric, space, -, _, .

    try {
        const response = await fetch('https://example.com/api/data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': 'your_csrf_token' // Include CSRF token if required
            },
            body: JSON.stringify({ data: sanitizedInput })
        });
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        // Handle response data
        console.log(data);
    } catch (error) {
        // Handle errors
        console.error('There was a problem with your fetch operation:', error);
    }
}

fetchData(); // Call the function to initiate the fetch operation


function submitForm() {
    const form = document.getElementById('myForm');
    const formData = new FormData(form);

    fetch('submit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        console.log(data); // Handle the response data
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
}


function submitForm_old() {
    const form = document.getElementById('alter-form');
    const formData = new FormData(form);

    fetch('SubmitUser.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                document.getElementById("progressbar").style.display = 'none';
                document.getElementById('alert-header').innerText = 'Not Updated/Deleted';
                document.getElementById('alert-box').style.display = 'block';

                // throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById("progressbar").style.display = 'none';
            document.getElementById('alert-header').innerText = data;
            document.getElementById('alert-box').style.display = 'block';
            console.log(data); // Handle the response data
        })
        .catch(error => {
            document.getElementById("progressbar").style.display = 'none';
            document.getElementById('alert-header').innerText = 'Not Updated/Deleted';
            document.getElementById('alert-box').style.display = 'block';
            console.error('There was a problem with the fetch operation:', error);
        });
}

// objMission.addEventListener('change', function (event) {
//     console.log('Input changed:', event.target.value);
// });
let text = "{\"error\":\"Login required\"}";
let cleanedText = text.replace(/^"|"$/g, ''); // Remove surrounding double quotes
let json = JSON.parse(cleanedText);
console.log(json.error);