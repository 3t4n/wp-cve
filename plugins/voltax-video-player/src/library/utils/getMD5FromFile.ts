import SparkMD5 from 'spark-md5'

export const getMD5FromFile = (file: File, binary = true, base64 = true): Promise<string> => {
    return new Promise((resolve, reject) => {
        const spark = new SparkMD5.ArrayBuffer()
        const fileReader = new FileReader()
        // @ts-ignore
        const blobSlice = File.prototype.slice || File.prototype.mozSlice || File.prototype.webkitSlice
        const chunkSize = 2097152 // Read in chunks of 2MB
        const chunks = Math.ceil(file.size / chunkSize)
        let currentChunk = 0

        const loadNext = () => {
            const start = currentChunk * chunkSize
            const end = start + chunkSize >= file.size ? file.size : start + chunkSize

            fileReader.readAsArrayBuffer(blobSlice.call(file, start, end))
        }

        fileReader.onload = (e: any) => {
            spark.append(e.target.result) // Append array buffer
            currentChunk += 1

            if (currentChunk < chunks) {
                loadNext()
            } else {
                let md5Result = spark.end(binary)
                if (base64) {
                    md5Result = btoa(md5Result)
                }
                resolve(md5Result) // Compute hash
            }
        }

        fileReader.onerror = (e) => {
            reject(e)
        }

        loadNext()
    })
}
