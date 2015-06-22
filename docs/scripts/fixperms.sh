#!/bin/bash

BASEDIR=`dirname $0`/..
GROUP="http"

if [ "$1" != "" ];
then
	GROUP="$1"
fi

mkdir -p $BASEDIR/{models,models/_base,models/_filters,controllers,views,runtime,migrations}
mkdir -p $BASEDIR/../{files,assets}

sudo chgrp -R $GROUP $BASEDIR/{models,models/_base,models/_filters,controllers,views,runtime,migrations,modules}
sudo chmod -R g+w $BASEDIR/{models,models/_base,models/_filters,controllers,views,runtime,migrations,modules}

sudo chgrp -R $GROUP $BASEDIR/../{files,assets}
sudo chmod -R g+w $BASEDIR/../{files,assets}
