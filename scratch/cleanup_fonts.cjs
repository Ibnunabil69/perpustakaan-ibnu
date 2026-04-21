const fs = require('fs');
const path = require('path');

function walkDir(dir, callback) {
  if (!fs.existsSync(dir)) return;
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
        // Remove font-poppins and font-sans classes if they are alone or in a list
        // Match "font-poppins" or " font-poppins" or "font-poppins " or "font-sans" etc.
        let newContent = content
            .replace(/\bfont-poppins\b/g, '')
            .replace(/\bfont-inter\b/g, '') // just in case
            .replace(/\s{2,}/g, ' ') // clean up extra spaces
            .trim();
        
        // Re-read to handle trimming issues if needed, but let's just do a simpler replace for now
        // to avoid destroying the template structure.
        
        let refinedContent = content.replace(/font-poppins/g, '').replace(/font-inter/g, '');
        
        if (content !== refinedContent) {
            fs.writeFileSync(filePath, refinedContent, 'utf8');
            console.log(`Cleaned up font classes in ${filePath}`);
        }
    }
});
