const fs = require('fs');
const path = require('path');

function walkDir(dir, callback) {
  fs.readdirSync(dir).forEach(f => {
    let dirPath = path.join(dir, f);
    let isDirectory = fs.statSync(dirPath).isDirectory();
    isDirectory ? 
      walkDir(dirPath, callback) : callback(path.join(dir, f));
  });
}

const viewsDir = 'c:\\laragon\\www\\perpustakaan-sekolah\\resources\\views';

walkDir(viewsDir, (filePath) => {
    if (filePath.endsWith('.blade.php')) {
        let content = fs.readFileSync(filePath, 'utf8');
        let newContent = content.replace(/navy-/g, 'slate-').replace(/teal-/g, 'indigo-');
        if (content !== newContent) {
            fs.writeFileSync(filePath, newContent, 'utf8');
            console.log(`Updated ${filePath}`);
        }
    }
});
