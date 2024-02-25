import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.atomic.AtomicInteger;

public class App {

    public static void main(String[] args) {
        int[] array = {1, 3, 5, 7, 9, 11, 13, 15, 17, 19, 21, 23, 25, 27, 29, 31};
        int target = 17; // El número que queremos buscar
        int numThreads = 4; // Número de hilos para usar en la búsqueda

        int segmentSize = array.length / numThreads;
        AtomicInteger foundIndex = new AtomicInteger(-1); // Para actualizar de manera segura entre hilos
        ExecutorService executor = Executors.newFixedThreadPool(numThreads);

        for (int i = 0; i < numThreads; i++) {
            final int start = i * segmentSize;
            final int end = (i == numThreads - 1) ? array.length : start + segmentSize;
            executor.submit(() -> {
                for (int j = start; j < end && foundIndex.get() == -1; j++) {
                    if (array[j] == target) {
                        foundIndex.set(j);
                        break; // Salir del bucle si se encuentra el número
                    }
                }
            });
        }
        executor.shutdown();
        while (!executor.isTerminated()) {
            // Esperar a que todos los hilos terminen
        }

        if (foundIndex.get() != -1) {
            System.out.println("Número " + target + " encontrado en el índice: " + foundIndex.get());
        } else {
            System.out.println("Número no encontrado.");
        }
    }
}
