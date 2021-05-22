import React from "react";
import ReactDOM from "react-dom";
import { McsWidget } from "./McsWidget";
import { LIST_MODE_CITIES } from "./constants";

//console.log(window.mcs_options?.title);

ReactDOM.render(
	<React.StrictMode>
		<McsWidget options={window.mcs?.options} data={window.mcs?.data} />
	</React.StrictMode>,
	document.getElementById("mcs-widget")
);
