import React from "react";
import ReactDOM from "react-dom";
import { McsWidget } from "./McsWidget";

ReactDOM.render(
	<React.StrictMode>
		<McsWidget options={window.mcs?.options} data={window.mcs?.data} />
	</React.StrictMode>,
	document.getElementById("mcs-widget")
);
