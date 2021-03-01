function gen_matrix(width, height){
    var matrix = [];

    for (var y = 0; y < height; y++){
        matrix.push(new Array(width));
    }
    return matrix;
}

function fill_matrix(matrix, element){
    for (var y = 0; y < matrix.length; y++){
        row = matrix[y];
        for (var x = 0; x < row.length; x++){
            row[x] = element.cloneNode(true);
        }
    }
}

function fill_color_matrix(matrix, element) {
    for (var y = 0; y < matrix.length; y++) {
        row = matrix[y];
        for (var x = 0; x < row.length; x++) {
            var new_element = element.cloneNode(true);
            new_element.textContent = `(${y + 1}, ${x + 1})`;
            row[x] = new_element;
        }
    }
}

function parse_input(text_input){
    var atoms = text_input.split(" ");
    
    var colors = [];
    var roads = [];

    for (var i = 0; i < atoms.length; i++){
        var curr_atom = atoms[i];
        var splitted = curr_atom.split("(");
        var name = splitted[0]
        var attrs = splitted[1].slice(0, -1).split(",");

        if (name == "color"){
            var info = {
                color_name: attrs[0],
                y: Number(attrs[1]),
                x: Number(attrs[2])
            };
            colors.push(info);
        }
        else if (name == "camino"){
            var info = {
                color: attrs[0],
                y1: Number(attrs[1]),
                x1: Number(attrs[2]),
                y2: Number(attrs[3]),
                x2: Number(attrs[4]),
            };

            if (Math.abs(info.y2 - info.y1) == 1) {
                var orientation = "vertical";
            }
            else {
                var orientation = "horizontal";
            }

            info.orientation = orientation;
            roads.push(info);
        }
    }
    return {colors: colors, roads: roads};
}

function gen_td_color(color_obj){
    var new_td = document.createElement("td");
    new_td.className = "objective " + color_obj.color_name;
    new_td.textContent = `(${color_obj.y}, ${color_obj.x})`;
    return new_td;
}

function gen_td_road(road_obj) {
    var new_td = document.createElement("td");
    new_td.className = `road ${road_obj.color} ${road_obj.orientation}`;
    return new_td;
}

function lowest_cord(arr1, arr2, horizontal){
    if (horizontal){
        if (arr1[0] < arr2[0]){
            return arr1;
        }
        else {
            return arr2;
        }
    }
    else {
        if (arr1[1] < arr2[1]){
            return arr1;
        }
        else {
            return arr2;
        }
    }
}


function push_to_road_table(horizontal_arr, vertical_arr, road_obj){

    arr1 = [road_obj.x1 - 1, road_obj.y1 - 1];
    arr2 = [road_obj.x2 - 1, road_obj.y2 - 1];

    if (road_obj.orientation == "horizontal"){
        var destiny_array = horizontal_arr;
        var cord = lowest_cord(arr1, arr2, true);
    }
    else {
        var destiny_array = vertical_arr;
        var cord = lowest_cord(arr1, arr2, false);
    }
    var td = gen_td_road(road_obj);
    destiny_array[cord[1]][cord[0]] = td;
}


function generate_table(x_max, y_max, colors, roads){

    var color_matrix = gen_matrix(x_max, y_max);
    var horizontal_roads = gen_matrix(x_max - 1, y_max);
    var vertical_roads = gen_matrix(x_max, y_max - 1);

    var blank_color = document.createElement("td");
    blank_color.className = "blankColor";

    var blank_horizontal = document.createElement("td");
    blank_horizontal.className = "blankHorizontal";

    var blank_vertical = document.createElement("td");
    blank_vertical.className = "blankVertical";

    fill_color_matrix(color_matrix, blank_color);
    fill_matrix(horizontal_roads, blank_horizontal);
    fill_matrix(vertical_roads, blank_vertical);

    colors.forEach(element => color_matrix[element.y - 1][element.x - 1] = gen_td_color(element));
    roads.forEach(element => push_to_road_table(horizontal_roads, vertical_roads, element));

    var table = document.createElement("table");

    for (var y = 0; y < y_max; y++){

        var tr = document.createElement("tr");
        for(var x = 0; x < x_max; x++){
            tr.appendChild(color_matrix[y_max - 1 - y][x]);
            if (x < x_max - 1){
                tr.appendChild(horizontal_roads[y_max - 1 - y][x]);
            }
        }
        table.appendChild(tr);

        if (y < y_max - 1){

            var tr = document.createElement("tr");
            for (var x = 0; x < x_max; x++){
                tr.appendChild(vertical_roads[y_max - 2 - y][x]);
                if (x < x_max - 1){
                    tr.appendChild(blank_vertical.cloneNode(true));
                }
            }
            table.appendChild(tr);
        }
    }
    return table;
}

function visualize(){
    var x_max = Number(document.getElementById("x_max_input").value);
    var y_max = Number(document.getElementById("y_max_input").value);
    var model_str = document.getElementById("model_input").value;

    var parsed = parse_input(model_str);
    var table = generate_table(x_max, y_max, parsed.colors, parsed.roads);

    var table_div = document.getElementById("visualizer");
    table_div.innerHTML = "";
    table_div.appendChild(table);
}




