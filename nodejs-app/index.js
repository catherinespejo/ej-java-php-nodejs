const { Worker, isMainThread, parentPort } = require('worker_threads');

function multiplyRowByMatrix(row, matrixB) {
  let resultRow = [];
  for (let j = 0; j < matrixB[0].length; j++) {
    let sum = 0;
    for (let k = 0; k < row.length; k++) {
      sum += row[k] * matrixB[k][j];
    }
    resultRow.push(sum);
  }
  return resultRow;
}

if (isMainThread) {
  const matrixA = [[1, 2], [3, 4]];
  const matrixB = [[2, 0], [1, 2]];

  console.log("Matriz A:");
  console.log(matrixA);

  console.log("\nMatriz B:");
  console.log(matrixB);

  const numWorkers = matrixA.length;
  const workers = [];
  const resultMatrix = [];

  for (let i = 0; i < numWorkers; i++) {
    workers[i] = new Worker(__filename);
    workers[i].on('message', (row) => {
      resultMatrix.push(row);
      if (resultMatrix.length === matrixA.length) {
        console.log("\nResultado de la Multiplicación:");
        console.log(resultMatrix);
      }
    });
    workers[i].postMessage({ matrixA: matrixA[i], matrixB: matrixB });
  }
} else {
  parentPort.on('message', (data) => {
    const resultRow = multiplyRowByMatrix(data.matrixA, data.matrixB);
    parentPort.postMessage(resultRow);
  });
}
