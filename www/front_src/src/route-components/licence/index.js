import React, { Component } from "react";
import TableComponent from "../../components/table";
import SearchComponent from "../../components/search";
import ActionIcons from "../../components/actionsIcons";


const tableBody = [{
  id: "1",
  status:"checked",
  checked: true,
  name: "Central Map Web Client",
  description: "Central Map Web Client lorem ipsum",
  version: "18.9.0",
  author: "Centreon Team",
  expiration: "17.05.2019",
  installed: true
},
{
  id: "2",
  status:"unchecked",
  checked: false,
  name: "Central Business Activity Monitoring",
  description: "Central Business Activity Monitoring lorem ipsum",
  version: "18.9.0",
  author: "Centreon",
  expiration: "17.05.2019",
  installed: false
},
{
  id: "3",
  status:"checked",
  checked: true,
  name: "Central Map Web Client",
  description: "Central Map Web Client lorem ipsum",
  version: "18.9.0",
  author: "Centreon Team",
  expiration: "17.05.2019",
  installed: true
}];


const tableConfig = [
  { label: 'All', key: 'checked', type: 'boolcheckbox' },
  { label: 'Name', key: 'name', type: 'text' },
  { label: 'Description', key: 'description', type: 'text' },
  { label: 'Version', key: 'version', type: 'text' },
  { label: 'Author', key: 'author', type: 'text' },
  { label: 'Expiration', key: 'expiration', type: 'text' },
  { label: 'Installed', key: 'installed', type: 'bool' }
]

class LicenceRoute extends Component {
  render() {
    return (
      <div>
        <div>
          <SearchComponent/>
          <ActionIcons/>
        </div>
          {/* to discuss - action icons and table should be in form ?? */}
        <TableComponent
          configuration={tableConfig}
          data={tableBody} />
      </div>

    )
  }
}

export default LicenceRoute;