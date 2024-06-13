'use strict'

let curdatetime = new Date();
let zoom = 4.0;
const colorRange = ['#cf7834', '#7347cb', '#39ab3d', '#ce4ec6', '#75a03c', '#d23e73', '#42a181', '#d8412f', '#6378c2'];
const flclr = ['#fb6a4a', '#ef3b2c', '#cb181d', '#a50f15', '#67000d'];

let filteredDepartment = [], filteredImplAgency = [], filteredScheme = [], filteredMission = [];
let filteredData = [], filteredImplAgencyData = [], filteredSchemeData = [], filteredMissionData = [];

const objMissionTable = document.getElementById('missionTable');
// const objNodalAgency = document.getElementById('NodalAgency');
const objStrategyTable = document.getElementById('strategyTable');
const objImplementTable = document.getElementById('implementTable');
const objSchemeTable = document.getElementById('schemeTable');

const objFinancialYear = document.getElementById('FinancialYear');
const objAlert = document.getElementById("alertmessage");
const objMessage = document.getElementById("spnmessage");
// const objQueryTable = document.getElementById("querydetails");
// const objQueryHeader = document.getElementById("queryheader");
const objSelectFilter = document.getElementById("selectFilter");

function initializeForm() {
    // const objMission = document.getElementById('Mission');
    // objMission.selectedIndex = 0;
    removeOptions(objMission);
    // const objStrategy = document.getElementById('Strategy');
    removeOptions(objStrategy);
    // const objImplementAgency = document.getElementById('ImplementAgency');
    removeOptions(objImplementAgency);
    // const objScheme = document.getElementById('Scheme');
    removeOptions(objScheme);
    // const objFinancialYear = document.getElementById('FinancialYear');
    objFinancialYear.selectedIndex = 0;
}

// let testDiv = document.getElementById("map");
// let rect = testDiv.getBoundingClientRect();
// document.getElementById("map").style.height = (window.innerHeight - rect.top) + "px";
// //console.log([testDiv.offsetTop, window.innerHeight, (window.innerHeight - rect.top)]);

// let map = new L.Map('map', {
//     zoom: 4
// });

// // let satellite = L.tileLayer(viz.leaflet.tiles.OpenStreetMap.satellite, {
// //     attribution: viz.leaflet.tiles.OpenStreetMap.attribution
// // })

// let osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
// let osmAttrib = 'Map data &copy; OpenStreetMap contributors';
// let osm = new L.TileLayer(osmUrl, {
//     attribution: osmAttrib
// });

// let osmLayer = L.tileLayer(
//     'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}@2x.png', {
//     attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
// }).addTo(map);

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
    }) //.addTo(map);

    // let nctbounds = districtLayers.getBounds();
    // map.fitBounds(nctbounds)
    // //L.rectangle(nctbounds, {color: "#ff7800", weight: 5}).addTo(map);
    // let lat = (nctbounds.getNorth() + nctbounds.getSouth()) / 2;
    // let lng = (nctbounds.getEast() + nctbounds.getWest()) / 2;

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
async function fetchBudget(target, code) {
    try {
        const jsonData = { target: target, code: code };
        const response = await fetch('fetchBudget.php', {
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
    console.log(selValue)
    if (selValue !== '-1') {
        // Add Nodal Agency
        let budgetDetails = await fetchBudget('mission', selValue);
        // console.log(budgetDetails);
        let cleanedBudget = budgetDetails.replace(/^"|"$/g, '');
        // console.log(cleanedBudget);
        let jsonBudget = JSON.parse(cleanedBudget);
        console.log(jsonBudget);
        /*
            bestpractive: "Best Practice&#39;s, If Any"
            briefdescription: "Scheme&#39;s Brief Description"
            budgetallocation: 256452
            budgetexpenditure: 23216
            budgetid: 2499782
            categoryactioncode: "MI"
            districtlist: ""
            expendituretca: 5804
            financialyear: "2024-2025"
            geocover: "punjab"
            implementingagencycode: 22
            implementingagencyname: "Punjab Energy Development Agency"
            keyprogressindicatorscode: null
            missioncode: 6
            missionname: "Enhanced Energy Efficiency and Solar Energy"
            nature: "0"
            ndccode: "NDC 4"
            nodalagency: "0"
            partneragencies: "Scheme&#39;s Partner Agencies (If any)"
            remarks: "Remark&#39;s"
            schemecode: 4
            schemename: "BOO Basis"
            sdgcode: "SDG 13, SDG 7, SDG 9"
            sourcefunding: "Central Sector Scheme"
            strategycode: "EN/N/6"
            strategyname: "Enhancing the private sector involvement in the renewable energy sector"
            weightage: 25        
        */
    }
}
//objMission.addEventListener('change', function () { changeMission(objMission) });

// async function changeStrategy(obj) {
//     'use strict'

//     let selValue = obj.value;
//     if (selValue !== '-1') {
//         let textOthers = await fetchData('others', selValue);
//         console.log(textOthers);
//         let cleanedOthers = textOthers.replace(/^"|"$/g, '');
//         console.log(textOthers);
//         let jsonOthers = JSON.parse(cleanedOthers);
//         console.log(jsonOthers);
//         // console.log(jsonOthers.length);
//         // console.log(Object.keys(jsonOthers));
//         // console.log(Object.keys(jsonOthers).length);
//         /*
//         0        :         "implement"
//         1        :         "scheme"
//         2        :         "categoryaction"
//         3        :         "nature"
//         4        :         "keyprogressindicators"
//         5        :         "categoryindicators"
//         */
//         // Implementing Agency
//         if (jsonOthers.implement.error === undefined) {
//             addOptions(objImplementAgency, jsonOthers.implement, "implementingagencycode", "implementingagencyname");
//         } else {
//             // alert(jsonOthers.categoryaction.error);
//             showMessage("alert-danger", jsonOthers.categoryaction.error);
//             removeOptions(objImplementAgency);
//         }
//         // Scheme
//         if (jsonOthers.scheme.error === undefined) {
//             addOptions(objScheme, jsonOthers.scheme, "schemecode", "schemename");
//         } else {
//             // alert(jsonOthers.scheme.error);
//             showMessage("alert-danger", jsonOthers.scheme.error);
//             removeOptions(objScheme);
//         }
//         // Category of Action
//         if (jsonOthers.categoryaction.error === undefined) {
//             objClimateAction.value = jsonOthers.categoryaction[0].categoryactioncode;
//         } else {
//             // alert(jsonOthers.categoryaction.error);
//             showMessage("alert-danger", jsonOthers.categoryaction.error);
//             objClimateAction.value = "";
//         }
//         // Nature
//         if (jsonOthers.nature.error === undefined) {
//             objNature.value = jsonOthers.nature[0].naturename
//         } else {
//             // alert(jsonOthers.nature.error);
//             showMessage("alert-danger", jsonOthers.nature.error);
//             objNature.value = "";
//         }

//         objLinkedSDG.value = "";
//         if (jsonOthers.sdg.error === undefined) {
//             let sdgvalue = ''
//             jsonOthers.sdg.forEach((d) => {
//                 sdgvalue += d.sdgcode + ", "
//             })
//             objLinkedSDG.value = sdgvalue.slice(0, -2);
//             // addOptions(objLinkedSDG, jsonOthers.sdg, "sdgcode", "sdgcode");
//         } else {
//             // alert(jsonOthers.sdg.error);
//             showMessage("alert-danger", jsonOthers.sdg.error);
//         }

//         objLinkedNDC.value = "";
//         // removeMultipleSelectOptions(objLinkedNDC)
//         // Nationally Determined Contributions
//         if (jsonOthers.ndc.error === undefined) {
//             let ndcvalue = ''
//             jsonOthers.ndc.forEach((d) => {
//                 ndcvalue += d.ndccode + ", "
//             })
//             objLinkedNDC.value = ndcvalue.slice(0, -2);
//             // addOptions(objLinkedNDC, jsonOthers.ndc, "ndccode", "ndccode");
//         } else {
//             // alert(jsonOthers.ndc.error);
//             showMessage("alert-danger", jsonOthers.ndc.error);
//         }

//         if (document.contains(document.getElementById("kpiTable"))) {
//             document.getElementById("kpiTable").remove();
//         }
//         // else {
//         //     lastDiv.appendChild(submitButton);
//         // }

//         // Key Progress Indicator
//         if (jsonOthers.keyprogressindicators.error === undefined) {

//             let kpicntr = 1;
//             let table = document.createElement('table');
//             table.id = "kpiTable";
//             table.classList.add('table');

//             let trHeader = document.createElement('tr');
//             ["Scheme's Performance Indicators", "Indicator's Status", "Unit", "Perforamance Indicator's Category"].forEach((f) => {
//                 let td = document.createElement('td');
//                 let text = document.createTextNode(f);
//                 td.appendChild(text);
//                 trHeader.appendChild(td);
//             })
//             table.appendChild(trHeader);

//             jsonOthers.keyprogressindicators.forEach((kpi) => {
//                 console.log(kpi.keyprogressindicatorsname + " : " + kpi.categoryindicatorsname);
//                 // for (let i = 1; i < 4; i++) {
//                 let tr = document.createElement('tr');

//                 let td1 = document.createElement('td');
//                 let td2 = document.createElement('td');
//                 let td3 = document.createElement('td');
//                 let td4 = document.createElement('td');

//                 //Indicator's Name
//                 let input1 = document.createElement('input');
//                 input1.id = 'kpi' + kpicntr;
//                 input1.name = 'kpi' + kpicntr;
//                 input1.type = 'text';
//                 // input1.classList.add("form-control");
//                 input1.setAttribute('value', kpi.keyprogressindicatorsname);
//                 input1.setAttribute('title', kpi.keyprogressindicatorsname);

//                 //Indicator's Status
//                 let input2 = document.createElement('input');
//                 input2.id = 'is' + kpicntr;
//                 input2.name = 'is' + kpicntr;
//                 input2.type = kpi.cellformat.trim() === "Number" ? 'number' : 'text';
//                 // input2.classList.add("form-control");
//                 input2.setAttribute('style', 'width:100px;');
//                 // class="form-control" 
//                 // console.log([kpi.cellformat.trim(),kpi.cellformat.trim() === "Number"])

//                 //Indicator's Unit
//                 let unit = kpi.units !== "Text Box" ? kpi.units : "";
//                 let input3 = document.createElement('input');
//                 input3.id = 'unit' + kpicntr;
//                 input3.name = 'unit' + kpicntr;
//                 input3.type = 'text';
//                 input3.setAttribute('style', 'width:100px;');
//                 input3.setAttribute('readonly', 'true');
//                 input3.setAttribute('value', unit);

//                 // Category Indicators Name
//                 let input4 = document.createElement('input');
//                 input4.id = 'ci' + kpicntr;
//                 input4.name = 'ci' + kpicntr;
//                 input4.type = 'text';
//                 input4.setAttribute('value', kpi.categoryindicatorsname);
//                 input4.setAttribute('style', 'width:100px;');

//                 td1.appendChild(input1);
//                 td2.appendChild(input2);
//                 td3.appendChild(input3);
//                 td4.appendChild(input4);
//                 tr.appendChild(td1);
//                 tr.appendChild(td2);
//                 tr.appendChild(td3);
//                 tr.appendChild(td4);

//                 table.appendChild(tr);
//                 // }
//                 objNokpi.value = kpicntr;
//                 kpicntr++;

//             })
//             // document.body.appendChild(table);
//             objKeyProgressIndicator.appendChild(table);
//         } else {
//             // alert(jsonOthers.keyprogressindicators.error);
//             objNokpi.value = 0;
//             showMessage("alert-danger", jsonOthers.keyprogressindicators.error);
//         }


//         // jsonOthers.keyprogressindicators.forEach((kpi) => {
//         //     console.log(kpi.keyprogressindicatorsname + " : " + kpi.categoryindicatorsname);
//         // })

//         // // Category of Indicators
//         // if (jsonOthers.categoryindicators.error === undefined) {
//         //     addOptions(objCategoryIndicators, jsonOthers.categoryindicators, "categoryindicatorscode", "categoryindicatorsname");
//         // } else {
//         //     alert(jsonOthers.categoryindicators.error);
//         // }
//     } else {
//         changeImplementAgency();
//     }

// }
//objStrategy.addEventListener('change', function () { changeStrategy(objStrategy) });

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
}());//.addTo(map);

function handleChange() {
    const inputValue = document.getElementById("tbsource").value;
    console.log("Input changed to:", inputValue);
}

function isNumber(objElement) {

    if (objElement.value.trim() == "") return;
    let value = parseFloat(objElement.value.trim());
    objElement.value = value;
    if (!value || isNaN(value)) {
        showMessage("alert-warning", "Not a valid number");
        objElement.value = parseFloat(objElement.value.trim());
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

// for (let year = new Date().getFullYear(); year > 2000; year--) {
//     let opt = document.createElement('option');
//     opt.value = year + '-' + (year + 1);
//     opt.innerHTML = year + '-' + (year + 1);
//     objFinancialYear.appendChild(opt);
// }
function getnerateCode(text) {
    let code = '';
    const myArray = text.split(" ");
    for (let c = 0; c < myArray.length; c++) {
        code += myArray[c].charAt(0);
    }
    return code;
}
let div = d3.select("body").append("div").attr("class", "tooltip");
function createPieChart(data, header) {
    document.getElementById('pieheader').innerHTML = header;
    document.getElementById('piechart').innerHTML = "";
    let margin = { top: 40, left: 40, right: 40, bottom: 40 };
    let width = 350;
    let height = 350;
    let radius = Math.min(width - 100, height - 100) / 2;
    let color = d3.scaleOrdinal()
        .range(["#002D62", "#0066b2", "#ffbb78", "#7ab51d", "#6b486b", "#e53517", "#7ab51d", "#ff7f0e", "#ffc400", "#e53517", "#6b486b"]);
    let arc = d3.arc()
        .outerRadius(radius - 100)
        .innerRadius(radius - 10);
    let arcOver = d3.arc()
        .outerRadius(radius + 50)
        .innerRadius(0);
    let svg = d3.select("#piechart").append("svg")
        .attr("width", width)
        .attr("height", height)
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");
    // let div = d3.select("body")
    //     .append("div")
    //     .attr("class", "tooltip");
    let pie = d3.pie()
        .sort(null)
        .value(function (d) { return d.Budget; });
    let g = svg.selectAll(".arc")
        .data(pie(data))
        .enter()
        .append("g")
        .attr("class", "arc")
        .on("mousemove", function (d) {
            let mouseVal = d3.mouse(this);
            // console.log(d.data)
            div.style("display", "none");
            div
                .html("Name:" + d.data.Name + "</br>" + "Value:" + d.data.Budget)
                .style("left", (d3.event.pageX + 12) + "px")
                .style("top", (d3.event.pageY - 10) + "px")
                .style("opacity", 1)
                .style("display", "block");
        })
        .on("mouseout", function () { div.html(" ").style("display", "none"); })
        .on("click", function (d) {
            if (d3.select(this).attr("transform") == null) {
                d3.select(this).attr("transform", "translate(42,0)");
            } else {
                d3.select(this).attr("transform", null);
            }
        });

    g.append("path")
        .attr("d", arc)
        .style("fill", function (d) { return color(d.data.Code); });

    svg.selectAll("text").data(pie(data)).enter()
        .append("text")
        .attr("class", "label1")
        .attr("transform", function (d) {
            let dist = radius + 15;
            let winkel = (d.startAngle + d.endAngle) / 2;
            let x = dist * Math.sin(winkel) - 4;
            let y = -dist * Math.cos(winkel) - 4;

            return "translate(" + x + "," + y + ")";
        })
        .attr("dy", "0.35em")
        .attr("text-anchor", "middle")

        .text(function (d) {
            return d.Budget;
        });
}
const budgetTitle = { Budget: "Budget Allocation", Expenditure: "Budget Expenditure", Climate: "Expenditure on Climate Action" };
function groupedBarChart(data, header) {
    document.getElementById('columnheader').innerHTML = header;
    document.getElementById('columnchart').innerHTML = "";
    const formatValue = d3.format(".2s");

    // set the dimensions and margins of the graph
    let margin = { top: 10, right: 30, bottom: 20, left: 50 },
        width = 460 - margin.left - margin.right,
        height = 300 - margin.top - margin.bottom;

    // append the svg object to the body of the page
    let svg = d3.select("#columnchart")
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");

    let tip = d3.tip()
        .attr('class', 'd3-tip')
        .offset([-10, 0])
        .html(function (d) {
            // console.log(budgetTitle[d.key])
            // return "<strong>Name:</strong>" + budgetTitle[d.key] + "<br><strong>Value:</strong>" + d.value;
            return budgetTitle[d.key] + "<br><strong>Value:</strong>" + d.value;
        });

    svg.call(tip);

    let subgroups = Object.keys(data[0]).slice(2)
    // console.log(subgroups);
    let groups = d3.map(data, function (d) { return (d.Code) }).keys()
    // Add X axis
    let x = d3.scaleBand()
        .domain(groups)
        .range([0, width])
        .padding([0.2])
    svg.append("g")
        .attr("transform", "translate(0," + height + ")")
        .call(d3.axisBottom(x).tickSize(0));

    // Add Y axis
    let y = d3.scaleLinear()
        // .domain([0, 40])
        .domain([0, d3.max(data, function (d) { return d3.max(subgroups, function (key) { return d[key]; }); })]).nice()
        .range([height, 0]);

    // Y Label
    svg.append("g")
        .call(d3.axisLeft(y).tickFormat(function (d) {
            return formatValue(d);
        }).ticks(10))
    // .append("text")
    // .attr("transform", "rotate(-90)")
    // .attr("y", 6)
    // .attr("dy", "-5.1em")
    // .attr("text-anchor", "end")
    // .attr("stroke", "black")
    // .text("Budget Allocation");

    // svg.append("g")
    //     .call(d3.axisLeft(y));

    // Another scale for subgroup position?
    let xSubgroup = d3.scaleBand()
        .domain(subgroups)
        .range([0, x.bandwidth()])
        .padding([0.05])

    // color palette = one color per subgroup
    let color = d3.scaleOrdinal()
        .domain(subgroups)
        // .range(['#e41a1c', '#377eb8', '#4daf4a'])
        // .range(["#8a89a6", "#7b6888", "#6b486b"])
        .range(["#002D62", "#0066b2", "#6CB4EE"])

    // Show the bars
    svg.append("g")
        .selectAll("g")
        // Enter in data = loop group per group
        .data(data)
        .enter()
        .append("g")
        .attr("transform", function (d) { return "translate(" + x(d.Code) + ",0)"; })
        .selectAll("rect")
        .data(function (d) { return subgroups.map(function (key) { return { key: key, value: d[key] }; }); })
        .enter().append("rect")
        .attr("x", function (d) { return xSubgroup(d.key); })
        .attr("y", function (d) { return y(d.value); })
        .attr("width", xSubgroup.bandwidth())
        .attr("height", function (d) { return height - y(d.value); })
        .attr("fill", function (d) { return color(d.key); })
        .on('mouseover', tip.show)
        .on('mouseout', tip.hide);

    // let keys = data.columns.slice(1);
    let legend = svg.append("g")
        .attr("font-family", "sans-serif")
        .attr("font-size", 10)
        .attr("text-anchor", "end")
        .selectAll("g")
        // .data(subgroups.slice().reverse())
        .data(subgroups)
        .enter().append("g")
        .attr("transform", function (d, i) { return "translate(0," + i * 20 + ")"; });

    legend.append("rect")
        .attr("x", width - 19)
        .attr("width", 19)
        .attr("height", 19)
        .attr("fill", color);

    legend.append("text")
        .attr("x", width - 24)
        .attr("y", 9.5)
        .attr("dy", "0.32em")
        .text(function (d) { return d; });
    // })

}

function createBarChart(data, header) {
    document.getElementById('columnheader').innerHTML = header;
    document.getElementById('columnchart').innerHTML = "";
    const formatValue = d3.format(".2s");

    let width = 350;
    let height = 250;
    let svg = d3.select("#columnchart").append("svg");
    // svg.attr("width",width+200);
    // svg.attr("height",height+200);
    svg.attr("width", width + 75);
    svg.attr("height", height + 75);
    // // Title of Chart
    // svg.append("text")
    //     .attr("transform", "translate(100,0)")
    //     .attr("x", 50)
    //     .attr("y", 50)
    //     .attr("font-size", "24px")
    //     .text("XYZ Foods Stock Price")

    let x = d3.scaleBand().range([0, width]).padding(0.1),
        y = d3.scaleLinear().range([height, 0]);

    let g = svg.append("g")
        .attr("transform", "translate(" + 50 + "," + 30 + ")");

    // d3.csv("xyz.csv", function (error, data) {
    // if (error) {
    //     throw error;
    // }

    x.domain(data.map(function (d) { return d.Code; }));
    y.domain([0, d3.max(data, function (d) { return d.Budget; })]);

    // X Label
    g.append("g")
        .attr("transform", "translate(0," + height + ")")
        .call(d3.axisBottom(x))
        .append("text")
        .attr("y", 30)
        .attr("x", 170)
        // .attr("y", height - 75)
        // .attr("x", width - 100)
        .attr("text-anchor", "end")
        .attr("stroke", "black")
        .text("Code");

    // Y Label
    g.append("g")
        .call(d3.axisLeft(y).tickFormat(function (d) {
            return formatValue(d);
        }).ticks(10))
        .append("text")
        .attr("transform", "rotate(-90)")
        .attr("y", 6)
        .attr("dy", "-5.1em")
        .attr("text-anchor", "end")
        .attr("stroke", "black")
        .text("Budget Allocation");

    g.selectAll(".bar")
        .data(data)
        .enter().append("rect")
        .attr("class", "bar")
        // .on("mouseover", onMouseOver) //Add listener for the mouseover event
        // .on("mouseout", onMouseOut)   //Add listener for the mouseout event
        .on("mousemove", function (d) {
            d3.select(this).attr('class', 'highlight');
            let mouseVal = d3.mouse(this);
            div.style("display", "none");
            div
                .html("Name:" + d.Name + "</br>" + "Value:" + d.Budget)
                .style("left", (d3.event.pageX + 12) + "px")
                .style("top", (d3.event.pageY - 10) + "px")
                .style("opacity", 1)
                .style("display", "block");
        })
        .on("mouseout", function () {
            d3.select(this).attr('class', 'bar');
            div.html(" ").style("display", "none");
        })
        .on("click", function (d) {
            if (d3.select(this).attr("transform") == null) {
                d3.select(this).attr("transform", "translate(42,0)");
            } else {
                d3.select(this).attr("transform", null);
            }
        })
        .attr("x", function (d) { return x(d.Code); })
        .attr("y", function (d) { return y(d.Budget); })
        .attr("width", x.bandwidth())
        .transition()
        .ease(d3.easeLinear)
        .duration(400)
        .delay(function (d, i) {
            return i * 50;
        })
        .attr("height", function (d) { return height - y(d.Budget); });
    // });

    //mouseover event handler function
    function onMouseOver(d, i) {
        d3.select(this).attr('class', 'highlight');
        // d3.select(this)
        //     .transition()     // adds animation
        //     .duration(400)
        //     .attr('width', x.bandwidth() + 5)
        //     .attr("y", function (d) { return y(d.Budget) - 10; })
        //     .attr("height", function (d) { return height - y(d.Budget) + 10; });

        g.append("text")
            .attr('class', 'val')
            .attr('x', function () {
                return x(d.Code);
            })
            .attr('y', function () {
                return y(d.Budget) - 15;
            })
            .text(function () {
                return [d.Budget];  // Value of the text
            });
    }

    //mouseout event handler function
    function onMouseOut(d, i) {
        // use the text label class to remove label on mouseout
        d3.select(this).attr('class', 'bar');
        // d3.select(this)
        //     .transition()     // adds animation
        //     .duration(400)
        //     .attr('width', x.bandwidth())
        //     .attr("y", function (d) { return y(d.Budget); })
        //     .attr("height", function (d) { return height - y(d.Budget); });

        d3.selectAll('.val')
            .remove()
    }
}
async function groupWiseSum(array) {
    let result = [];
    array.reduce(function (res, value) {
        if (!res[value.missionCode]) {
            res[value.missionCode] = { Code: value.missionCode, Name: value.missionName, Budget: 0, Expenditure: 0, Climate: 0, Table: 'Mission' };
            result.push(res[value.missionCode])
        }
        res[value.missionCode].Budget += value.Budget;
        res[value.missionCode].Expenditure += value.Expenditure;
        res[value.missionCode].Climate += value.Climate;

        if (!res[value.strategyCode]) {
            res[value.strategyCode] = { Code: value.strategyCode, Name: value.strategyName, Budget: 0, Expenditure: 0, Climate: 0, Table: 'Strategy' };
            result.push(res[value.strategyCode])
        }
        res[value.strategyCode].Budget += value.Budget;
        res[value.strategyCode].Expenditure += value.Expenditure;
        res[value.strategyCode].Climate += value.Climate;

        if (!res[value.implementCode]) {
            res[value.implementCode] = { Code: value.implementCode, Name: value.implementName, Budget: 0, Expenditure: 0, Climate: 0, Table: 'Implement' };
            result.push(res[value.implementCode])
        }
        res[value.implementCode].Budget += value.Budget;
        res[value.implementCode].Expenditure += value.Expenditure;
        res[value.implementCode].Climate += value.Climate;

        if (!res[value.schemeCode]) {
            res[value.schemeCode] = { Code: value.schemeCode, Name: value.schemeName, Budget: 0, Expenditure: 0, Climate: 0, Table: 'Scheme' };
            result.push(res[value.schemeCode])
        }
        res[value.schemeCode].Budget += value.Budget;
        res[value.schemeCode].Expenditure += value.Expenditure;
        res[value.schemeCode].Climate += value.Climate;

        return res;
    }, {});
    // console.log(result)
    return result;
}
// groupWiseSum();

// function displayBudget(data) {
//     let chartData = [];
//     let tableDetails = "<table>";
//     tableDetails += "<tr><th>Mission</th><th>Nodal Agency</th><th>Strategy</th><th>Implementing Agency</th><th>Scheme</th><th>Nature</th><th>Geographical Coverage</th><th>District</th><th>Lined SDG</th><th>Linked NDC</th><th>Category Action</th><th>Source of Funding</th><th>Financial Year</th><th>Budget Allocation</th><th>Budget Expenditure</th><th>Weightage for Climate Action</th><th>Expenditure for Climate Action</th></tr>";
//     data.forEach((d) => {
//         let tmpData = {};
//         tmpData['Code'] = getnerateCode(d.missionname);
//         tmpData['Name'] = d.missionname;
//         tmpData['Budget'] = d.budgetallocation;
//         tmpData['Expenditure'] = d.budgetexpenditure;
//         tmpData['Climate'] = d.expendituretca;
//         tableDetails += "<tr><td>" + d.missionname + "</td><td>" + d.nodalagency + "</td><td>" + d.strategyname + "</td><td>" + d.implementingagencyname + "</td><td>" + d.schemename + "</td><td>" + d.nature + "</td><td>" + d.geocover + "</td><td>" + d.districtlist + "</td><td>" + d.sdgcode + "</td><td>" + d.ndccode + "</td><td>" + d.categoryactioncode + "</td><td>" + d.sourcefunding + "</td><td>" + d.financialyear + "</td><td>" + d.budgetallocation + "</td><td>" + d.budgetexpenditure + "</td><td>" + d.weightage + "</td><td>" + d.expendituretca + "</td></tr>";
//         chartData.push(tmpData);
//     })
//     tableDetails += "</table>";
//     objQueryTable.innerHTML = tableDetails;

//     let result = groupWiseSum(chartData);
//     console.log(result);
// }

async function displayBudgetTable(data) {
    let chartData = [];
    // let tableDetails = "<table>";
    // tableDetails += "<tr><th>Mission</th><th>Nodal Agency</th><th>Strategy</th><th>Implementing Agency</th><th>Scheme</th><th>Nature</th><th>Geographical Coverage</th><th>District</th><th>Lined SDG</th><th>Linked NDC</th><th>Category Action</th><th>Source of Funding</th><th>Financial Year</th><th>Budget Allocation</th><th>Budget Expenditure</th><th>Weightage for Climate Action</th><th>Expenditure for Climate Action</th></tr>";
    data.forEach((d) => {
        let tmpData = {};
        tmpData['missionCode'] = getnerateCode(d.missionname);
        tmpData['strategyCode'] = d.strategycode;
        tmpData['implementCode'] = getnerateCode(d.implementingagencyname);
        tmpData['schemeCode'] = getnerateCode(d.schemename);
        tmpData['missionName'] = d.missionname;
        tmpData['strategyName'] = d.strategyname;
        tmpData['implementName'] = d.implementingagencyname;
        tmpData['schemeName'] = d.schemename;
        tmpData['Budget'] = d.budgetallocation;
        tmpData['Expenditure'] = d.budgetexpenditure;
        tmpData['Climate'] = d.expendituretca;
        // tableDetails += "<tr><td>" + d.missionname + "</td><td>" + d.nodalagency + "</td><td>" + d.strategyname + "</td><td>" + d.implementingagencyname + "</td><td>" + d.schemename + "</td><td>" + d.nature + "</td><td>" + d.geocover + "</td><td>" + d.districtlist + "</td><td>" + d.sdgcode + "</td><td>" + d.ndccode + "</td><td>" + d.categoryactioncode + "</td><td>" + d.sourcefunding + "</td><td>" + d.financialyear + "</td><td>" + d.budgetallocation + "</td><td>" + d.budgetexpenditure + "</td><td>" + d.weightage + "</td><td>" + d.expendituretca + "</td></tr>";
        chartData.push(tmpData);
    })

    // tableDetails += "</table>";
    // objQueryTable.innerHTML = tableDetails;

    // console.log(new Date().getTime())
    let result = await groupWiseSum(chartData);
    // console.log(new Date().getTime())
    console.log(result);
    // console.log(new Date().getTime())
    
    let missionTable = "<table>";
    missionTable += "<tr><th>Mission</th><th>Budget Allocation</th><th>Budget Expenditure</th><th>Expenditure for Climate Action</th></tr>";
    result.filter((f)=>{
        return f.Table==='Mission';
    }).forEach((d)=>{
        
        missionTable += "<tr><td>" + d.Name + "</td><td style='text-align: right;'>" + d.Budget.toLocaleString('en-IN') + "</td><td style='text-align: right;'>" + d.Expenditure.toLocaleString('en-IN') + "</td><td style='text-align: right;'>" + d.Climate.toLocaleString('en-IN') + "</td></tr>";
    })
    missionTable += "</table>";
    objMissionTable.innerHTML = missionTable;

    let strategyTable = "<table>";
    strategyTable += "<tr><th>Strategy</th><th>Budget Allocation</th><th>Budget Expenditure</th><th>Expenditure for Climate Action</th></tr>";
    result.filter((f)=>{
        return f.Table==='Strategy';
    }).forEach((d)=>{
        
        strategyTable += "<tr><td>" + d.Name + "</td><td style='text-align: right;'>" + d.Budget.toLocaleString('en-IN') + "</td><td style='text-align: right;'>" + d.Expenditure.toLocaleString('en-IN') + "</td><td style='text-align: right;'>" + d.Climate.toLocaleString('en-IN') + "</td></tr>";
    })
    strategyTable += "</table>";
    objStrategyTable.innerHTML = strategyTable;

    let implementTable = "<table>";
    implementTable += "<tr><th>Implement Agency</th><th>Budget Allocation</th><th>Budget Expenditure</th><th>Expenditure for Climate Action</th></tr>";
    result.filter((f)=>{
        return f.Table==='Implement';
    }).forEach((d)=>{
        
        implementTable += "<tr><td>" + d.Name + "</td><td style='text-align: right;'>" + d.Budget.toLocaleString('en-IN') + "</td><td style='text-align: right;'>" + d.Expenditure.toLocaleString('en-IN') + "</td><td style='text-align: right;'>" + d.Climate.toLocaleString('en-IN') + "</td></tr>";
    })
    implementTable += "</table>";
    objImplementTable.innerHTML = implementTable;

    let schemeTable = "<table>";
    schemeTable += "<tr><th>Scheme</th><th>Budget Allocation</th><th>Budget Expenditure</th><th>Expenditure for Climate Action</th></tr>";
    result.filter((f)=>{
        return f.Table==='Scheme';
    }).forEach((d)=>{
        
        schemeTable += "<tr><td>" + d.Name + "</td><td style='text-align: right;'>" + d.Budget.toLocaleString('en-IN') + "</td><td style='text-align: right;'>" + d.Expenditure.toLocaleString('en-IN') + "</td><td style='text-align: right;'>" + d.Climate.toLocaleString('en-IN') + "</td></tr>";
    })
    schemeTable += "</table>";
    objSchemeTable.innerHTML = schemeTable;
}
let jsonBudget = [];
async function getCurrentData() {
    jsonBudget = [];
    let currentmonth = new Date().getMonth() + 1;
    let currentyear = new Date().getFullYear();
    let finyear = currentmonth > 3 ? currentyear + '-' + (currentyear + 1) : (currentyear - 1) + '-' + currentyear;
    // console.log(finyear);
    let budgetDetails = await fetchBudget('all', finyear);
    // console.log(budgetDetails);
    let cleanedBudget = budgetDetails.replace(/^"|"$/g, '');
    // console.log(cleanedBudget);
    jsonBudget = JSON.parse(cleanedBudget);
    // console.log(jsonBudget);
    displayBudgetTable(jsonBudget);
}
getCurrentData();

async function changeFinancialYear(obj) {
    let selValue = obj.value;
    console.log(selValue)
    if (selValue !== '-1') {
        let budgetDetails = await fetchBudget('all', selValue);
        // console.log(budgetDetails);
        let cleanedBudget = budgetDetails.replace(/^"|"$/g, '');
        // console.log(cleanedBudget);
        jsonBudget = JSON.parse(cleanedBudget);
        // console.log(jsonBudget);
        displayBudget(jsonBudget);
    }
}
objFinancialYear.addEventListener('change', function () { changeFinancialYear(objFinancialYear) });

function changeFilter(obj) {
    let selValue = obj.value;
    console.log(selValue)
    if (selValue !== '-1') {
        let chartData = [];
        if (selValue === 'mission') {
            jsonBudget.forEach((d) => {
                let tmpData = {};
                tmpData['Code'] = getnerateCode(d.missionname);
                tmpData['Name'] = d.missionname;
                tmpData['Budget'] = d.budgetallocation;
                tmpData['Expenditure'] = d.budgetexpenditure;
                tmpData['Climate'] = d.expendituretca;
                chartData.push(tmpData);
            })
            // console.log(chartData);
            let result = groupWiseSum(chartData);
            groupedBarChart(result, 'Mission');
            createPieChart(result, 'Mission');
        }
        else if (selValue === 'strategy') {
            jsonBudget.forEach((d) => {
                let tmpData = {};
                tmpData['Code'] = d.strategycode;
                tmpData['Name'] = d.strategyname;
                tmpData['Budget'] = d.budgetallocation;
                tmpData['Expenditure'] = d.budgetexpenditure;
                tmpData['Climate'] = d.expendituretca;
                chartData.push(tmpData);
            })
            let result = groupWiseSum(chartData);
            groupedBarChart(result, 'Strategy');
            createPieChart(result, 'Strategy');
        }
        else if (selValue === 'implementingagency') {
            jsonBudget.forEach((d) => {
                let tmpData = {};
                tmpData['Code'] = getnerateCode(d.implementingagencyname);
                tmpData['Name'] = d.implementingagencyname;
                tmpData['Budget'] = d.budgetallocation;
                tmpData['Expenditure'] = d.budgetexpenditure;
                tmpData['Climate'] = d.expendituretca;
                chartData.push(tmpData);
            })
            let result = groupWiseSum(chartData);
            groupedBarChart(result, 'Implementing Agency');
            createPieChart(result, 'Implementing Agency');
        }
        else if (selValue === 'scheme') {
            jsonBudget.forEach((d) => {
                let tmpData = {};
                tmpData['Code'] = getnerateCode(d.schemename);
                tmpData['Name'] = d.schemename;
                tmpData['Budget'] = d.budgetallocation;
                tmpData['Expenditure'] = d.budgetexpenditure;
                tmpData['Climate'] = d.expendituretca;
                chartData.push(tmpData);
            })

            let result = groupWiseSum(chartData);
            groupedBarChart(result, 'Scheme');
            createPieChart(result, 'Scheme');
        }
        else if (selValue === 'categoryaction') {
            jsonBudget.forEach((d) => {
                let tmpData = {};
                tmpData['Code'] = d.categoryactioncode; //getnerateCode(d.schemename);
                tmpData['Name'] = d.categoryactioncode;
                tmpData['Budget'] = d.budgetallocation;
                tmpData['Expenditure'] = d.budgetexpenditure;
                tmpData['Climate'] = d.expendituretca;
                chartData.push(tmpData);
            })
            let result = groupWiseSum(chartData);
            groupedBarChart(result, 'Category of Climate Action');
            createPieChart(result, 'Category of Climate Action');
        }
        else if (selValue === 'sourcefunding') {
            jsonBudget.forEach((d) => {
                let tmpData = {};
                tmpData['Code'] = getnerateCode(d.sourcefunding); //getnerateCode(d.schemename);
                tmpData['Name'] = d.sourcefunding;
                tmpData['Budget'] = d.budgetallocation;
                tmpData['Expenditure'] = d.budgetexpenditure;
                tmpData['Climate'] = d.expendituretca;
                chartData.push(tmpData);
            })

            let result = groupWiseSum(chartData);
            groupedBarChart(result, 'Source of Funding');
            createPieChart(result, 'Source of Funding');
        }
        // else if(selValue !== '') {}
        // else if(selValue !== '') {}
    }
}
// objSelectFilter.addEventListener('change', function () { changeFilter(objSelectFilter) });
objSelectFilter.selectedIndex = 0;

// const color = d3.scaleOrdinal(d3.schemeCategory10);
// console.log(color.range());
/**
<rect x="3" y="18" width="34" height="10" fill="#d53e4f"/>
<rect x="38" y="18" width="34" height="10" fill="#f46d43"/>
<rect x="73" y="18" width="34" height="10" fill="#fdae61"/>
<rect x="108" y="18" width="34" height="10" fill="#fee08b"/>
<rect x="143" y="18" width="34" height="10" fill="#ffffbf"/>
<rect x="178" y="18" width="34" height="10" fill="#e6f598"/>
<rect x="213" y="18" width="34" height="10" fill="#abdda4"/>
<rect x="248" y="18" width="34" height="10" fill="#66c2a5"/>
<rect x="283" y="18" width="34" height="10" fill="#3288bd"/>
 */