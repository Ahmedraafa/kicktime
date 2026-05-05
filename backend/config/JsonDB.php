class JsonResult {
    private $data;
    private $currentIndex = 0;

    public function __construct($data) {
        $this->data = array_values($data);
    }

    public function rowCount() {
        return count($this->data);
    }

    public function fetch($mode = null) {
        if ($this->currentIndex < count($this->data)) {
            return $this->data[$this->currentIndex++];
        }
        return false;
    }
}

class JsonDB {
    private $dataDir;

    public function __construct($dataDir = null) {
        $this->dataDir = $dataDir ?: __DIR__ . '/../data/';
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0777, true);
        }
    }

    private function getFilePath($table) {
        return $this->dataDir . $table . '.json';
    }

    public function readAll($table) {
        $file = $this->getFilePath($table);
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
            return [];
        }
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }

    public function readBy($table, $filters = []) {
        $data = $this->readAll($table);
        return array_filter($data, function($item) use ($filters) {
            foreach ($filters as $key => $value) {
                if (!isset($item[$key]) || $item[$key] != $value) {
                    return false;
                }
            }
            return true;
        });
    }

    public function findOne($table, $filters = []) {
        $results = $this->readBy($table, $filters);
        return !empty($results) ? array_values($results)[0] : null;
    }

    public function insert($table, $data) {
        $allData = $this->readAll($table);
        
        // Auto-increment ID
        $maxId = 0;
        foreach ($allData as $item) {
            if (isset($item['id']) && $item['id'] > $maxId) {
                $maxId = $item['id'];
            }
        }
        $data['id'] = $maxId + 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $allData[] = $data;
        $this->save($table, $allData);
        return $data['id'];
    }

    public function update($table, $id, $newData) {
        $allData = $this->readAll($table);
        $found = false;
        foreach ($allData as &$item) {
            if ($item['id'] == $id) {
                $item = array_merge($item, $newData);
                $found = true;
                break;
            }
        }
        if ($found) {
            $this->save($table, $allData);
        }
        return $found;
    }

    public function delete($table, $id) {
        $allData = $this->readAll($table);
        $initialCount = count($allData);
        $allData = array_filter($allData, function($item) use ($id) {
            return $item['id'] != $id;
        });
        if (count($allData) < $initialCount) {
            $this->save($table, array_values($allData));
            return true;
        }
        return false;
    }

    private function save($table, $data) {
        $file = $this->getFilePath($table);
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
